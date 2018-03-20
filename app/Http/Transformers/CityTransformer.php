<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2/18/18
 * Time: 4:50 PM
 */

namespace App\Http\Transformers;


use App\City;
use League\Fractal\TransformerAbstract;
class CityTransformer extends TransformerAbstract
{
    public function transform(City $city)
    {

        return [
            'id' => $city->id,
            'name' => $city->name,
            'zip' => $city->zip,
            'province' => $city->province
        ];
    }
}