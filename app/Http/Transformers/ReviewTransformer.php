<?php

namespace App\Http\Transformers;

use App\Review;
use App\Transformers\UserTransformer;
use League\Fractal\TransformerAbstract;
use Spatie\Fractalistic\ArraySerializer;

class ReviewTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Review $review)
    {
        return [
            'id' => $review->id,
            'reviewer' => fractal($review->fromuser, new UserTransformer(), new ArraySerializer())->toArray(),
            'comment' => $review->comment,
            'rating' => $review->score,
            'created_at' => (string) $review->created_at,
            'updated_at' => (string) $review->updated_at
        ];
    }
}
