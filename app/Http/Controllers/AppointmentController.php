<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Events\MessageSent;
use App\Message;
use App\Offer;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\ApiResponseHelper;
use DB;
use JWTAuth;
class AppointmentController extends Controller
{
    public function save(Request $request){
        $input = $request->all();
        $user = JWTAuth::parseToken()->toUser();
        $rules = [
            'title' => 'required|string|max:191',
            'date' => 'required|date',
            'time' => 'date_format:H:i',
            'creator_id' => 'required'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }
        $appointment = new Appointment($input);
        $appointment->location = json_encode($input['location']);
        DB::beginTransaction();
        $appointment->save();
        if($input['offer_id'] && $input['receiver_id']){
            $offer = Offer::find($input['offer_id']);
            $message = new Message(['message' => json_encode($appointment->toArray()) , 'sender_id' => $user->id, 'receiver_id' => $input['receiver_id'], 'type' => 'request']);
            $offer->messages()->save($message);
            $message = $message->with(['sender' => function($q){
                $q->select()->get();
            }, 'receiver'])->find($message->id);
            broadcast(new MessageSent($user, $message->receiver, $message))->toOthers();
        }
        DB::commit();
        return ApiResponseHelper::success(['appointment' => $appointment]);
    }
}
