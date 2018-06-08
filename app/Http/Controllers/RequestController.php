<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Message;
use App\Notifications\NewOffer;
use App\Offer;
use App\Request as RequestModel;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use DB;
class RequestController extends Controller
{
    //
    public function index(Request $request) {
        $user = JWTAuth::parseToken()->toUser();
        $requests = $user->requests()->withCount('offers')->with(['offers' => function($q) {
            $q->with(['service' => function($query){
                $query->select('name', 'id', 'logo', 'price_estimate', 'rate')->get();
            }])->get();
        }])->get();
        return ApiResponseHelper::success(['requests' => $requests]);
    }
    public function get(Request $request, $id) {
        $user = JWTAuth::parseToken()->toUser();
        $req = $user->requests()->with(['offers' => function($q) {
            $q->with(['service' => function($query){
                $query->select('name', 'id', 'logo', 'price_estimate', 'rate')->orderBy('price_estimate')->get();
            }])->orderBy('status')->get();
        }])->where('id', $id)->first();
        if (!$req) {
            return ApiResponseHelper::error('request bestaat niet', 404);
        }
        if ($req->user_id != $user->id) {
            return ApiResponseHelper::error('request hoort niet bij jou', 404);
        }

        return ApiResponseHelper::success(['request' => $req]);
    }
    public function save(Request $request){
        $user = JWTAuth::parseToken()->toUser();
        $input = $request->all();
        $rules = [
            'ids' => 'required',
            'title' => 'required',
            'due_date' => 'required|date',
            'description' => 'required',
            'city_id' => 'required'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $error = $validator->messages();
            return ApiResponseHelper::error($error);
        }
        $input['city_id'] = (integer) $input['city_id'];
        $input['user_id'] = $user->id;
        $serviceIds = json_decode($input['ids'], true);
        $req = new \App\Request($input);
        DB::beginTransaction();
        try{
            $req->save();
            foreach($serviceIds as $id){
                $offer = new Offer;
                $offer->service_id = $id['id'];
                $req->offers()->save($offer);
                $messagebody = 'Datum : ' . $req->due_date . "\n" . 'Locatie : ' . $req->city->zip . ', ' . $req->city->name . "\n" . 'Beschrijving : ' . trim($req->description);
                $message = new Message(['message' => $messagebody, 'sender_id' => $user->id, 'receiver_id' => $offer->service->user->id, 'type' => 'request']);
                $offer->messages()->save($message);
                $offer->service->user->notify(new NewOffer($user, $offer));
            }
            DB::commit();
            return ApiResponseHelper::success(['data' => $req->toArray()]);
        }catch (\Exception $ex){
            DB::rollBack();
            return ApiResponseHelper::error($ex , 500);
        }
    }
    public function update(Request $request, $id){
        $user = JWTAuth::parseToken()->toUser();
        $input = $request->all();
        $req = RequestModel::where('id', $id)->withCount('offers')->with(['offers' => function($q) {
            $q->with(['service' => function($query){
                $query->select('name', 'id', 'logo', 'price_estimate', 'rate')->get();
            }])->get();
        }])->first();
        if (!$req) {
            return ApiResponseHelper::error('request bestaat niet', 404);
        }
        if ($req->user_id != $user->id) {
            return ApiResponseHelper::error('request hoort niet bij jou', 404);
        }
        try{
            $req->update($input);
            return ApiResponseHelper::success(['request' => $req]);
        }catch(\Exception $ex){
            return ApiResponseHelper::error('Er ging iets mis', 500);
        }
    }
    public function delete(Request $request, $id){
        $user = JWTAuth::parseToken()->toUser();

        $req = RequestModel::find($id);
        if (!$req) {
            return ApiResponseHelper::error('request bestaat niet', 404);
        }
        if ($req->user_id != $user->id) {
            return ApiResponseHelper::error('request hoort niet bij jou', 404);
        }
        try{
            $req->forceDelete();
            return ApiResponseHelper::success([], 'request succesvol verwijderd!');
        }catch(\Exception $ex){
            return ApiResponseHelper::error('Er ging iets mis', 500);
        }
    }
}
