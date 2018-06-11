<?php

namespace App\Http\Transformers;

use App\Offer;
use League\Fractal\TransformerAbstract;
use Spatie\Fractalistic\ArraySerializer;

class OfferTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Offer $offer)
    {
        return [
            'id' => $offer->id,
            'hired' => $offer->hired,
            'new_messages' => $offer->messages->count(),
            'status' => $offer->status,
            'accepted' => $offer->accepted,
            'request_id' => $offer->request_id,
            'request' => $offer->request,
            'service_id' => $offer->service_id,
            'service' => fractal($offer->service, new ServiceTransformer(), new ArraySerializer())->parseIncludes('user')->toArray(),
            'created_at' => (string) $offer->created_at,
            'updated_at' => (string) $offer->updated_at
        ];
    }
}
