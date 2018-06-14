<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Transformers\ReviewTranformer;
use App\Notifications\NewReview;
use App\Offer;
use App\Review;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Spatie\Fractalistic\ArraySerializer;
use Validator;
use DB;
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
            $offer->service_reviewed = true;
            $notifyto = $model->user;
        }
        if($offer->service->user_id == $user->id){
            $model = $offer->request->user;
            $offer->user_reviewed = true;
            $notifyto = $model;
        }
        $review = new Review();
        $review->comment = $input['review'];
        $review->score = $input['rating'];
        $review->user_id = $user->id;

        DB::beginTransaction();
        $offer->save();
        $model->reviews()->save($review);
        $notifyto->notify(new NewReview($user, $offer, $review));
        DB::commit();

        return ApiResponseHelper::success(['review' => $review->toArray()]);
    }
    public function userreviews($id){
        $user = User::find($id);
        if (!$user) {
            return ApiResponseHelper::error('User bestaat niet', 404);
        }
        $paginator = $user->reviews()->with('fromuser')->orderBy('id', 'desc')->paginate(5);
        $reviews = $paginator->getCollection();
        $reviews = fractal($reviews, new ReviewTranformer())->paginateWith(new IlluminatePaginatorAdapter($paginator))->toArray();
        return ApiResponseHelper::success(['reviews' => $reviews]);
    }
    public function servicereviews($id){
        $service = Service::find($id);
        if (!$service) {
            return ApiResponseHelper::error('User bestaat niet', 404);
        }
        $paginator = $service->reviews()->with('fromuser')->orderBy('id', 'desc')->paginate(5);
        $reviews = $paginator->getCollection();
        $reviews = fractal($reviews, new ReviewTranformer())->paginateWith(new IlluminatePaginatorAdapter($paginator))->toArray();
        return ApiResponseHelper::success(['reviews' => $reviews]);
    }
}
