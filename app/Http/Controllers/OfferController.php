<?php

namespace App\Http\Controllers;

use App\Message;
use App\Offer;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use JWTAuth;
use Validator;

class OfferController extends Controller
{
    public function get(Request $request, $reqId, $id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $offer = Offer::with(['service' => function ($q) {
            $q->with('user', 'city');
        }])->find($id);
        if (!$offer || $offer->request_id != $reqId) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        if ($offer->request->user_id != $user->id) {
            return ApiResponseHelper::error('Offer hoort niet bij jou', 404);
        }
        return ApiResponseHelper::success(['offer' => $offer]);
    }

    public function getMessages(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $offer = Offer::find($id);
        if (!$offer) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        if ($offer->request->user_id != $user->id) {
            return ApiResponseHelper::error('Offer hoort niet bij jou', 404);
        }
        $messages = $offer->messages()->with(['sender' => function($q){
            $q->select()->get();
        }, 'receiver'])->get();
        return ApiResponseHelper::success(['messages' => $messages]);
    }

    public function sendMessage(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $offer = Offer::find($id);
        if (!$offer) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        if ($offer->request->user_id != $user->id) {
            return ApiResponseHelper::error('Offer hoort niet bij jou', 404);
        }
        $receiver = $offer->service->user_id;
        $input = $request->all();
        $rules = [
            'message' => 'required'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }
        $message = new Message(['message' => trim($input['message']), 'sender_id' => $user->id, 'receiver_id' => $receiver]);
        $offer->messages()->save($message);
        $message = $message->with(['sender' => function($q){
            $q->select()->get();
        }, 'receiver'])->find($message->id);
        return ApiResponseHelper::success(['message' => $message]);
    }
}
