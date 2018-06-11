<?php

namespace App\Http\Transformers;

use App\Request;
use League\Fractal\TransformerAbstract;
use Spatie\Fractalistic\ArraySerializer;

class RequestTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Request $request)
    {
        return [
           'id' => (int) $request->id,
            'title' => $request->title,
            'user_id' => (int) $request->user_id,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'city_id' => (int) $request->city_id,
            'created_at' => (string) $request->created_at,
            'offers' => fractal($request->offers, new OfferTransformer(), new ArraySerializer())->toArray()
        ];
    }
}
