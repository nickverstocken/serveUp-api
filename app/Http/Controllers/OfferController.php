<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
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
}
