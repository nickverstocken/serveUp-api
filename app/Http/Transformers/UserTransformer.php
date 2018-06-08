<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2/18/18
 * Time: 4:42 PM
 */

namespace App\Transformers;
use App;
use App\User;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'city', 'service'
    ];
    public function transform(User $user)
    {

        return [
            'id' => $user->id,
            'fname' => trim($user->fname),
            'name' => trim($user->name),
            'introduction' => trim($user->introduction),
            'email' => trim($user->email),
            'city_id' => $user->city_id,
            'address' => trim($user->address),
            'zip' => trim($user->city->zip),
            'city' => trim($user->city->name),
            'province' => trim($user->city->province),
            'country' => trim($user->country),
            'picture' => trim($user->picture),
            'picture_thumb' => trim($user->picture_thumb),
            'creation_date' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
            'role' => $user->role,
            'rating' => $user->reviews()->get()->average('score'),
            'number_ratings' => $user->reviews()->count('id')
        ];
    }
    public function includecity(User $user)
    {
        if (!$user->city) {
            return null;
        }
        return $this->item($user->city, App::make(App\Http\Transformers\CityTransformer::class));
    }
    public function includeservice(User $user){
        if(!$user->services){
            return null;
        }
        return $this->collection($user->services, App::make(App\Http\Transformers\ServiceTransformer::class));
    }
}