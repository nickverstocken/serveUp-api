<?php

namespace App\Http\Controllers;

use App\UserVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Transformers\UserTransformer;
use Spatie\Fractalistic\ArraySerializer;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Response;
use App\User;
use DB;
use Hash;
use Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use App\Helpers\ApiResponseHelper;
use App\Http\Helpers\ImageUpload;

class AuthController extends Controller
{
    private $fractal;
    private $userTransformer;

    function __construct(Manager $fractal, UserTransformer $userTransformer)
    {
        $this->fractal = $fractal;
        $this->userTransformer = $userTransformer;
        $this->fractal->setSerializer(new ArraySerializer());
    }
    public function register(Request $request)
    {
        $rules = [
            'fname' => 'required|max:50',
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'address' => 'max:191',
            'city_id' => 'integer',
            'picture' => 'image|mimes:jpg,png,jpeg',
            'role' => 'required|in:user,service'
        ];
        $input = $request->all();
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $error = $validator->messages();
            return ApiResponseHelper::error($error);
        }
        try {
            $input['password'] = bcrypt($input['password']);
            DB::beginTransaction();
            $user = User::create($input);
            if ($input['picture']) {
                $file = $input['picture'];
                $extension = $file->getClientOriginalExtension();

                $name = $user->id;
                $path = ImageUpload::saveImage($input['picture'], 500, 500, 'profile', $extension, $name . '/avatar');
                $user->picture = $path . '?' . Carbon::now()->timestamp;
                $path = ImageUpload::saveImage($input['picture'], 100, 100, 'profile_thumb', $extension, $name . '/avatar');
                $user->picture_thumb = $path . '?' . Carbon::now()->timestamp;
                $user->save();
            }
            $verification_code = str_random(30); //Generate verification code
            UserVerification::create(['user_id' => $user->id, 'token' => $verification_code]);
            DB::commit();
            Return ApiResponseHelper::success(['user' => $user, 'verification' => $verification_code]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return ApiResponseHelper::error($ex->getMessage(), 500);
        }

    }
    public function sendVerificationMail(Request $request){
        $verficationCode = $request->get('verification');
        $userMail = $request->get('email');
        $userName = $request->get('name');
        $userFname = $request->get('fname');
        $subject = "Welkom bij Serve-Up!";
        Mail::send('email.verify', ['username' => $userFname . ' ' . $userName, 'verification_code' => $verficationCode],
            function ($mail) use ($userMail, $userName, $userFname , $subject) {
                $mail->from(getenv('FROM_EMAIL_ADDRESS'), "nick@serveup.be");
                $mail->to($userMail, $userFname . ' ' . $userName);
                $mail->subject($subject);
            });
        Return ApiResponseHelper::success([], 'Mail successfully send!');
    }
    public function checkEmail(Request $request){
        try{
            $user = JWTAuth::parseToken()->toUser();
            $rules = [
                'email' => 'required|email|max:255|unique:users,email,'. $user->id,
            ];

        }catch(\Exception $ex){
            $rules = [
                'email' => 'required|email|max:255|unique:users',
            ];
        }

        $input = $request->only('email');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $error = $validator->messages();
            return ApiResponseHelper::error($error, 200);
        }else{
            return ApiResponseHelper::success([], 'Email ok');
        }
    }
    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token', $verification_code)->first();
        if (!is_null($check)) {
            $user = User::find($check->user_id);
            if ($user->is_verified == 1) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account already verified..'
                ]);
            }
            $user->update(['is_verified' => 1]);
            DB::table('user_verifications')->where('token', $verification_code)->delete();
            return view('welcome', [
            ]);

        }
        return view('error', [
        ]);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $input = $request->only('email', 'password');
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $error = $validator->messages();
            return ApiResponseHelper::error($error, 400);
        }
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'is_verified' => 1
        ];
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return ApiResponseHelper::error('Invalid Credentials. Please make sure you entered the right information and you have verified your email address.', 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return ApiResponseHelper::error('could not create token', 500);
        }
        // all good so return the token
        $user = Auth::user();
        $user = new Item($user, $this->userTransformer);
        $this->fractal->parseIncludes($request->get('include', ''));
        $user = $this->fractal->createData($user);
        $user = $user->toArray();
        return ApiResponseHelper::success(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $token = JWTAuth::getToken();
        try {
            JWTAuth::invalidate($token);
            return ApiResponseHelper::success(['message' => 'logged out']);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }

    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(['success' => false, 'error' => ['email' => $error_message]], 404);
        }
        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });
        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['success' => false, 'error' => $error_message], 401);
        }
        return response()->json([
            'success' => true, 'data' => ['message' => 'A reset email has been sent! Please check your email.']
        ]);
    }

    public function getAuthenticatedUser(Request $request)
    {
       //$token = JWTAuth::getToken();
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return ApiResponseHelper::error('token_expired', 401);
        } catch (TokenInvalidException $e) {
            return ApiResponseHelper::error('token_invalid', 401);
        } catch (JWTException $e) {
            return ApiResponseHelper::error('token_absent', 401);
        }
        $user = JWTAuth::parseToken()->toUser();
        $user = new Item($user, $this->userTransformer);
        $this->fractal->parseIncludes($request->get('include', ''));
        $user = $this->fractal->createData($user);
        $user = $user->toArray();
        return ApiResponseHelper::success(['user' => $user]);
    }

    public function refreshToken()
    {
        try {
            $token = JWTAuth::getToken();
            $newtoken = JWTAuth::refresh($token);
            return response()->json([
                'token' => $newtoken
            ]);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], 400);
        } catch (JWTException $e) {
            return response()->json(['token_absent'], 400);
        }
    }
}