<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Events\MessageEditted;
use App\Events\MessageSent;
use App\Http\Transformers\FullCalendarTransformer;
use App\Message;
use App\Offer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Spatie\Fractalistic\ArraySerializer;
use Validator;
use App\Helpers\ApiResponseHelper;
use DB;
use JWTAuth;
use Response;

class AppointmentController extends Controller
{
    private $fractal;
    private $fullCalendarTranformer;

    function __construct(Manager $fractal, FullCalendarTransformer $fullCalendarTranformer)
    {
        $this->fractal = $fractal;
        $this->fullCalendarTranformer = $fullCalendarTranformer;
        $this->fractal->setSerializer(new ArraySerializer());
    }

    public function getAppointments(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $query = collect(new Appointment);
        $events = [];

        $start = $request->input('start', Carbon::now()->toDateString());
        $end = $request->input('end', Carbon::now()->addMonth(1)->toDateString());

        $query = Appointment::whereHas('offer', function ($q) use ($user) {
            $q->whereHas('request', function ($q2) use ($user) {
                $q2->where('user_id', $user->id);
            });
        })->whereBetween('date', [$start, $end])->get();;

        $appointments = new Collection($query, $this->fullCalendarTranformer);
        $appointments = $this->fractal->createData($appointments);
        $appointments = $appointments->toArray();
        $events['cat0'] = $appointments;

        foreach ($user->services as $key=>$service) {
            $id = $service->id;

            $query = Appointment::whereHas('offer', function ($q) use ($user, $id) {
                $q->where('service_id', $id)->whereHas('service', function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                })->with('service');
            })->whereBetween('date', [$start, $end])->get();

            $appointments = new Collection($query, $this->fullCalendarTranformer);
            $appointments = $this->fractal->createData($appointments);
            $appointments = $appointments->toArray();
            $events['cat' . ($key + 1)] = $appointments;
        }

        return ApiResponseHelper::success(['appointments' => $events]);
    }
    public function show(Request $request, $id){
        $appointment = Appointment::with(['offer.service.user', 'offer.request.user'])->find($id);
        if(!$appointment){
            return ApiResponseHelper::error('appointment not found', 404);
        }
        return ApiResponseHelper::success(['appointment' => $appointment]);
    }
    public function save(Request $request)
    {
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
        $appointment->location = $input['location']  ;
        DB::beginTransaction();
        $appointment->save();
        if ($input['offer_id'] && $input['receiver_id']) {
            $offer = Offer::find($input['offer_id']);
            $message = new Message(['message' => json_encode($appointment->toArray()), 'sender_id' => $user->id, 'receiver_id' => $input['receiver_id'], 'type' => 'date']);
            $offer->messages()->save($message);
            $message = $message->with(['sender' => function ($q) {
                $q->select()->get();
            }, 'receiver'])->find($message->id);
            broadcast(new MessageSent($user, $message->receiver, $message))->toOthers();
        }
        DB::commit();
        return ApiResponseHelper::success(['appointment' => $appointment, 'message' => $message]);
    }

    public function delete(Request $request, $id)
    {
        $input = $request->all();
        $user = JWTAuth::parseToken()->toUser();
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return ApiResponseHelper::error('Afspraak bestaat niet');
        }
        $deletmsg = $user->fname . ' ' . $user->name . ' heeft de afspraak "' . $appointment->title . '" geannuleerd';
        DB::beginTransaction();
        if ($input['offer_id'] && $input['receiver_id'] && $input['message_id']) {
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
            $infomessage = $infomessage->with(['sender' => function ($q) {
                $q->select()->get();
            }, 'receiver'])->find($infomessage->id);
            broadcast(new MessageSent($user, $infomessage->receiver, $infomessage))->toOthers();
        }
        $appointment->delete();
        DB::commit();
        return ApiResponseHelper::success(['appointment' => $appointment], 'Afspraak succesvol verwijderd!');
    }

    public function accept(Request $request, $id)
    {
        $input = $request->all();
        $user = JWTAuth::parseToken()->toUser();
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return ApiResponseHelper::error('Afspraak bestaat niet');
        }
        $msg = $user->fname . ' ' . $user->name . ' heeft de afspraak "' . $appointment->title . '" geaccepteerd';
        DB::beginTransaction();
        if ($input['offer_id'] && $input['receiver_id'] && $input['message_id']) {
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
            $infomessage = $infomessage->with(['sender' => function ($q) {
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
