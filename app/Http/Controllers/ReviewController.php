<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Offer;
use App\Review;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;
class ReviewController extends Controller
{
    public function save(Request $request, $offer_id){
        $rules = [
            'review' => 'required',
            'rating' => 'required|integer|min:1|max:5'
        ];
        $input = $request->all();
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }
        $user = JWTAuth::parseToken()->toUser();
        $offer = Offer::find($offer_id);
        if (!$offer) {
            return ApiResponseHelper::error('Offer bestaat niet', 404);
        }
        if ($offer->request->user_id != $user->id && $offer->service->user_id != $user->id) {
            return ApiResponseHelper::error('Offer hoort niet bij jou', 404);
        }
        if(!$offer->hired){
            return ApiResponseHelper::error('Je bent niet aangenomen dus kan je ook niet reviewen', 403);
        }

        if($offer->request->user_id == $user->id){
            $model = $offer->service;

        }
        if($offer->service->user_id == $user->id){
            $model = $offer->request->user;
        }
        $review = new Review();
        $review->comment = $input['review'];
        $review->score = $input['rating'];
        $review->user_id = $user->id;
        $model->reviews()->save($review);

        return ApiResponseHelper::success(['review' => $review->toArray()]);
    }
    public function userreviews($id){
        $user = User::find($id);
        if (!$user) {
            return ApiResponseHelper::error('User bestaat niet', 404);
        }
        dd($user->reviews);
    }
    public function servicereviews($id){
        $service = Service::find($id);
        if (!$service) {
            return ApiResponseHelper::error('User bestaat niet', 404);
        }
        dd($service->reviews()->with('fromuser')->get()->toArray());
    }
}
