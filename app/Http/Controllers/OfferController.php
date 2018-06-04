<?php

namespace App\Http\Controllers;

use App\Events\MessageEditted;
use App\Events\MessageSent;
use App\Image;
use App\Message;
use App\Offer;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use JWTAuth;
use Validator;
use DB;
use Illuminate\Support\Facades\Storage;
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

    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();
        $query = Offer::whereHas('request', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $query = $query->with(['service' => function ($q) {
            $q->with('user');
        }])->get();
        $query->each(function ($offer) {
            $offer->load('latestMessage');
        });
        return ApiResponseHelper::success(['offers' => $query]);
    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $query = Offer::find($id)->whereHas('request', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        if (!$query) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        $input = $request->all();
        $query->update($input);
        $query->load('latestMessage');
        return ApiResponseHelper::success(['offers' => $query]);
    }

    public function sendPriceOffer(Request $request, $id)
    {
        $input = $request->all();
        $rules = [
            'price' => 'required',
            'rate' => 'required',
            'creator_id' => 'required',
            'receiver_id' => 'required'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }

        $priceOffer = [
            'price' => $input['price'],
            'rate' => $input['rate'],
            'creator_id' => $input['creator_id']
        ];
        $user = JWTAuth::parseToken()->toUser();
        $offer = Offer::find($id);
        $message = new Message(['message' => json_encode($priceOffer), 'sender_id' => $user->id, 'receiver_id' => $input['receiver_id'], 'type' => 'price']);
        $offer->messages()->save($message);
        $message = $message->with(['sender' => function ($q) {
            $q->select()->get();
        }, 'receiver'])->find($message->id);
        broadcast(new MessageSent($user, $message->receiver, $message))->toOthers();
        return ApiResponseHelper::success(['message' => $message]);

    }

    public function actionPriceOffer(Request $request, $id)
    {
        $input = $request->all();
        $rules = [
            'action' => 'required',
            'message_id' => 'required',
            'receiver_id' => 'required'
        ];
        if($input['action'] == 'geaccepteerd'){
            $rules['rate'] = 'required';
            $rules['price'] = 'required';
        }
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }
        $user = JWTAuth::parseToken()->toUser();

        $deletmsg = $user->fname . ' ' . $user->name . ' heeft het prijsvoorstel ' . $input['action'];
        $offer = Offer::find($id);
        if($input['action'] == 'geaccepteerd'){
         $offer->price_offer = $input['price'];
         $offer->rate = $input['rate'];
        }
        DB::beginTransaction();
        $offer->save();
        $message = Message::find($input['message_id']);
        $receiver = User::find($input['receiver_id']);
        $messagebody = json_decode($message->message, true);
        if($input['action'] == 'geaccepteerd'){
            $messagebody['cancelled'] = false;
            $messagebody['approved'] = true;
        }else{
            $messagebody['cancelled'] = true;
            $messagebody['approved'] = false;
        }

        $message->message = json_encode($messagebody);
        $infomessage = new Message(['message' => $deletmsg, 'sender_id' => $user->id, 'receiver_id' => $input['receiver_id'], 'type' => 'info']);
        $offer->messages()->save($infomessage);
        $message->save();
        broadcast(new MessageEditted($user, $receiver, $message, 'edit'))->toOthers();
        $infomessage = $infomessage->with(['sender' => function ($q) {
            $q->select()->get();
        }, 'receiver'])->find($infomessage->id);
        broadcast(new MessageSent($user, $infomessage->receiver, $infomessage))->toOthers();
        DB::commit();
        return ApiResponseHelper::success(['message' => $message]);
    }
    public function saveAttachments(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->toUser();
        $rules = [
            'files.*' => 'file|max:2000000'
        ];
        $input = $request->all();
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }
        $offer = Offer::find($id);
        if (!$offer) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        if ($offer->request->user_id != $user->id && $offer->service->user_id != $user->id) {
            return ApiResponseHelper::error('Offer hoort niet bij jou', 404);
        }
        $receiver = null;
        if($offer->request->user_id == $user->id){
            $receiver = $offer->service->user_id;
        }
        if($offer->service->user_id == $user->id){
            $receiver = $offer->request->user_id;
        }
        $files = $request->file('files');
        $nbr = count($files) - 1;
        $nbr = $nbr <= 15 ? $nbr : 15;
        $mediafiles = Collect(new Image());
        DB::beginTransaction();
        foreach (range(0, $nbr) as $index) {
            $media = new Image();
            $name = $files[$index]->getClientOriginalName();
            $mime = $files[$index]->getMimeType();
            $path = $files[$index]->store(
                'media/'.$request->user()->id, 'public'
            );
            $media->path = Storage::disk('public')->url('/' . $path);
            $media->name = $name;
            $media->mime_type = $mime;
            $offer->images()->save($media);
            $mediafiles[] = $media;
        }
        $message = new Message(['message' => json_encode($mediafiles), 'sender_id' => $user->id, 'receiver_id' => $receiver, 'type' => 'document']);
        $offer->messages()->save($message);
        $message = $message->with(['sender' => function ($q) {
            $q->select()->get();
        }, 'receiver'])->find($message->id);
        broadcast(new MessageSent($user, $message->receiver, $message))->toOthers();
        DB::commit();
        return ApiResponseHelper::success(['media' => $mediafiles, 'message' => $message]);
    }
}
