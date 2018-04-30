<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Pusher\Pusher;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponseHelper;
class PusherController extends Controller
{
    private $pusher;
    public function __construct() {
        $this->pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => 'eu',
            'encrypted' => true,
        ]);
    }
    /**
     * Authenticates logged-in user in the Pusher JS app
     * For presence channels
     */
    /**
     * @param Request $request
     * @return mixed
     */
    public function postAuth(Request $request)
    {
        //We see if the user is logged in our laravel application.
        if(Auth::check())
        {
            //Fetch User Object
            $user =  Auth::user();
            //Presence Channel information. Usually contains personal user information.
            //See: https://pusher.com/docs/client_api_guide/client_presence_channels
            $presence_data = array('name' => $user->fname." ".$user->name);
            //Registers users' presence channel.
            echo $this->pusher->presence_auth($request->get('channel_name'), $request->get('socket_id'), $user->id, $presence_data);
        }
        else
        {
            return ApiResponseHelper::error('Forbidden', 403);
        }
    }
}
