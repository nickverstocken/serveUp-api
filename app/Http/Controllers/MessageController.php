<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\ApiResponseHelper;
use App\Message;
use App\Events\MessageSent;
use JWTAuth;
use App\Offer;

class MessageController extends Controller
{
    public function sendMessage(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $offer = Offer::find($id);
        $request_user = $offer->request->user_id;
        $service_user = $offer->service->user_id;
        if (!$offer) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        if ($request_user != $user->id && $service_user != $user->id) {
            return ApiResponseHelper::error('Offer hoort niet bij jou', 404);
        }
        if ($request_user == $user->id) {
            $receiver = $offer->service->user_id;
        } else {
            $receiver = $offer->request->user_id;
        }

        $input = $request->all();
        $rules = [
            'message' => 'required'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }

        if (isset($input['type'])) {
            switch ($input['type']) {
                case 'date':
                    break;
                case 'document':
                    break;
                case 'info':
                    break;
                case 'image':
                    break;
                default:

            }
        }
        $message = new Message(['message' => trim($input['message']), 'sender_id' => $user->id, 'receiver_id' => $receiver]);
        $offer->messages()->save($message);

        $message = $message->with(['sender' => function ($q) {
            $q->select()->get();
        }, 'receiver'])->find($message->id);
        broadcast(new MessageSent($user, $message->receiver, $message))->toOthers();
        return ApiResponseHelper::success(['message' => $message]);
    }

    public function getMessages(Request $request, $offerId)
    {
        $user = JWTAuth::parseToken()->toUser();
        $offer = Offer::find($offerId);
        if (!$offer) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        $request_user = $offer->request->user_id;
        $service_user = $offer->service->user_id;
        if ($request_user != $user->id && $service_user != $user->id) {
            return ApiResponseHelper::error('Offer hoort niet bij jou', 404);
        }
        $messages = $offer->messages()->with(['sender' => function ($q) {
            $q->select()->get();
        }, 'receiver'])->orderBy('updated_at')->get();
        return ApiResponseHelper::success(['offer' => $offer, 'messages' => $messages]);
    }

    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $messages = [];

        $meta = Offer::whereHas('messages')->with(['messages' => function ($q) use ($user) {
            $q->where('read_at', null)->where('receiver_id', $user->id);
        }])->get()->pluck('messages')->flatten()->count();

        if ($user->role == 'service') {
            $messages['service_messages'] = Offer::whereHas('service', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['service', 'request.user'])->limit(15)->get();

            $messages['service_messages']->each(function ($offer) {
                $offer->load('latestMessage');
            });
        }
        $messages['personal_messages'] = $user->requests()->with(['offers' => function ($q) {
            $q->with(['service', 'request.user']);
            $q->limit(10);
        }])->get()->pluck('offers')->flatten();

        $messages['personal_messages']->each(function ($offer) {
            $offer->load('latestMessage');
        });
        return ApiResponseHelper::success(['messages' => $messages, 'unread' => $meta]);
    }

    public function markAsRead(Request $request, $offerId)
    {
        $user = JWTAuth::parseToken()->toUser();
        $offer = Offer::find($offerId);
        if (!$offer) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        $request_user = $offer->request->user_id;
        $service_user = $offer->service->user_id;
        if ($request_user != $user->id && $service_user != $user->id) {
            return ApiResponseHelper::error('Offer hoort niet bij jou', 404);
        }
        $offer->messages()->where('receiver_id', $user->id)->where('read_at', null)->update(['read_at' => now()]);
        return ApiResponseHelper::success([], 'marked as read');
    }
    public function markAllAsRead(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $offers = Offer::whereHas('messages')->with(['messages' => function ($q) use ($user) {
            $q->where('read_at', null)->where('receiver_id', $user->id);
        }])->get();

        $offers->each(function ($offer) use ($user){
            $offer->messages()->where('receiver_id', $user->id)->where('read_at', null)->update(['read_at' => now()]);
        });


        return ApiResponseHelper::success([], 'marked as read');
    }

}
