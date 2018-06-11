<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Helpers\ImageUpload;
use App\User;
use Illuminate\Http\Request;
use Spatie\Fractalistic\ArraySerializer;
use Validator;
use JWTAuth;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Transformers\UserTransformer;
use Hash;
use Carbon\Carbon;
class UserController extends Controller
{
    private $fractal;
    private $userTransformer;

    function __construct(Manager $fractal, UserTransformer $userTransformer)
    {
        $this->fractal = $fractal;
        $this->userTransformer = $userTransformer;
        $this->fractal->setSerializer(new ArraySerializer());
    }
    public function get(Request $request, $id){
        $user = User::find($id);
        if(!$user){
            return ApiResponseHelper::error('user not found', 404);
        }
        $user = fractal($user, new UserTransformer(), new ArraySerializer())->parseIncludes('city')->toArray();
        return ApiResponseHelper::success(['user' => $user]);
    }
    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        if (!$user) {
            return ApiResponseHelper::error('user not found', 404);
        }
        $input = $request->all();
        $rules = [
            'fname' => 'required|max:50',
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'required',
            'introduction' => 'nullable|string|max:300'
        ];
        if ($request->hasFile('picture')) {
            $rules['picture'] = 'nullable|image|mimes:jpg,png,jpeg';
        }
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }
        if ($request->hasFile('picture')) {
            $file = $input['picture'];
            $extension = $file->getClientOriginalExtension();

            $name = $user->id;
            $path = ImageUpload::saveImage($input['picture'], 500, 500, 'profile', $extension, $name . '/avatar');
            $path_thumb = ImageUpload::saveImage($input['picture'], 100, 100, 'profile_thumb', $extension, $name . '/avatar');
            $input['picture'] = $path . '?' . Carbon::now()->timestamp;
            $user['picture_thumb'] = $path_thumb . '?' . Carbon::now()->timestamp;
        } else {
            $input['picture'] = $user->picture;
        }
        $user->update($input);
        $user = new Item($user, $this->userTransformer);
        $user = $this->fractal->createData($user);
        $this->fractal->parseIncludes(['city', 'service']);
        $user = $user->toArray();
        return ApiResponseHelper::success(['user' => $user], 'user update success');
    }
    public function updatePassword(Request $request) {
        $user = JWTAuth::parseToken()->toUser();
        $rules = [
            'password' => 'required',
            'new_password' => 'required|confirmed|min:6'
        ];
        $input = $request->all();
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $error = $validator->messages();
            return ApiResponseHelper::error($error);
        }

       // $input['password'] = bcrypt($input['password']);
        if(!Hash::check($input['password'], $user->password)){
            return ApiResponseHelper::error(['error' => 'Verkeerd wachtwoord ingegeven'], 422);
        }
        $input['password'] = Hash::make($input['new_password']);
        $user->update($input);
        return ApiResponseHelper::success(['user' => $user], 'user update success');
    }
    public function notifications()
    {
        $user = JWTAuth::parseToken()->toUser();
        return ApiResponseHelper::success(['notifications' => $user->unreadnotifications()->get(), 'unread' => $user->unreadNotifications()->count()]);
    }
}
