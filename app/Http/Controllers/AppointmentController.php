<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Events\MessageEditted;
use App\Events\MessageSent;
use App\Message;
use App\Offer;
use App\User;
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
            $message = new Message(['message' => json_encode($appointment->toArray()) , 'sender_id' => $user->id, 'receiver_id' => $input['receiver_id'], 'type' => 'date']);
            $offer->messages()->save($message);
            $message = $message->with(['sender' => function($q){
                $q->select()->get();
            }, 'receiver'])->find($message->id);
            broadcast(new MessageSent($user, $message->receiver, $message))->toOthers();
        }
        DB::commit();
        return ApiResponseHelper::success(['appointment' => $appointment, 'message' => $message]);
    }
    public function delete(Request $request, $id){
        $input = $request->all();
        $user = JWTAuth::parseToken()->toUser();
        $appointment = Appointment::find($id);
        if(!$appointment){
            return ApiResponseHelper::error('Afspraak bestaat niet');
        }
        $deletmsg = $user->fname . ' ' . $user->name . ' heeft de afspraak "' . $appointment->title . '" geannuleerd';
        DB::beginTransaction();
        if($input['offer_id'] && $input['receiver_id'] && $input['message_id']){
            $offer = Offer::find($input['offer_id']);
            $message = Message::find($input['message_id']);
            $receiver = User::find($input['receiver_id']);
            $messagebody = json_decode($message->message, true);
            $messagebody['cancelled'] = true;
            $message->message = json_encode($messagebody);
            $infomessage = new Message(['message' => $deletmsg, 'sender_id' => $user->id, 'receiver_id' => $input['receiver_id'], 'type' => 'info']);
            $offer->messages()->save($infomessage);
            $message->save();
            broadcast(new MessageEditted($user, $receiver, $message, 'edit'))->toOthers();
            $infomessage = $infomessage->with(['sender' => function($q){
                $q->select()->get();
            }, 'receiver'])->find($infomessage->id);
            broadcast(new MessageSent($user, $infomessage->receiver, $infomessage))->toOthers();
        }
        $appointment->delete();
        DB::commit();
        return ApiResponseHelper::success(['appointment' => $appointment], 'Afspraak succesvol verwijderd!');
    }
    public function accept(Request $request, $id){
        $input = $request->all();
        $user = JWTAuth::parseToken()->toUser();
        $appointment = Appointment::find($id);
        if(!$appointment){
            return ApiResponseHelper::error('Afspraak bestaat niet');
        }
        $msg = $user->fname . ' ' . $user->name . ' heeft de afspraak "' . $appointment->title . '" geaccepteerd';
        DB::beginTransaction();
        if($input['offer_id'] && $input['receiver_id'] && $input['message_id']){
            $offer = Offer::find($input['offer_id']);
            $receiver = User::find($input['receiver_id']);
            $message = Message::find($input['message_id']);
            $messagebody = json_decode($message->message, true);
            $messagebody['approved'] = true;
            $message->message = json_encode($messagebody);
            $infomessage = new Message(['message' => $msg, 'sender_id' => $user->id, 'receiver_id' => $input['receiver_id'], 'type' => 'info']);
            $offer->messages()->save($infomessage);
            $message->save();
            broadcast(new MessageEditted($user, $receiver, $message, 'edit'))->toOthers();
            $infomessage = $infomessage->with(['sender' => function($q){
                $q->select()->get();
            }, 'receiver'])->find($infomessage->id);
            broadcast(new MessageSent($user, $infomessage->receiver, $infomessage))->toOthers();
        }
        $appointment->approved = true;
        $appointment->save();
        DB::commit();
        return ApiResponseHelper::success(['appointment' => $appointment], 'Afspraak succesvol geaccepteerd!');
    }
}
