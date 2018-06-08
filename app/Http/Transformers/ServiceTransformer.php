<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 3/20/18
 * Time: 11:40 PM
 */

namespace App\Http\Transformers;
use App;
use App\Service;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class ServiceTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'city'
    ];
    public function transform(Service $service)
    {

        return [
            'id' => $service->id,
            'user_id' => $service->user_id,
            'name' => trim($service->name),
            'description' => trim($service->description),
            'subcategory_id' => $service->subcategory ? $service->subcategory->id : null,
            'sub_category' =>  $service->subcategory ? $service->subcategory()->with('category')->first() : null,
            'city_id' => $service->city_id,
            'address' => trim($service->address),
            'city' => $service->city,
            'country' => trim($service->country),
            'tel' => trim($service->tel),
            'experience' => $service->experience,
            'website' => trim($service->website),
            'social_networks' => $service->social_networks,
            'logo' => trim($service->logo),
            'banner' => trim($service->banner),
            'business_hours' => $service->business_hours,
            'areas_of_service' => $service->areas_of_service,
            'max_km' => trim($service->max_km),
            'price_estimate' => $service->price_estimate,
            'faq' => $service->faq,
            'rate' => $service->rate,
            'price_extras' => $service->price_extras,
            'standard_response' => trim($service->standard_response),
            'creation_date' => $service->created_at->toDateTimeString(),
            'tags' => $service->tags,
            'rating' => $service->reviews()->get()->average('score'),
            'number_ratings' => $service->reviews()->count('id')
        ];
    }
}