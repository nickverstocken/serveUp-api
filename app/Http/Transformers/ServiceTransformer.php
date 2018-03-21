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
        'city', 'faq'
    ];
    public function transform(Service $service)
    {

        return [
            'id' => $service->id,
            'name' => trim($service->name),
            'description' => trim($service->description),
            'city_id' => $service->city_id,
            'address' => trim($service->address),
            'zip' => trim($service->city->zip),
            'city' => trim($service->city->name),
            'province' => trim($service->city->province),
            'country' => trim($service->country),
            'tel' => trim($service->tel),
            'experience' => $service->experience,
            'website' => trim($service->website),
            'facebook' => trim($service->facebook),
            'twitter' => trim($service->twitter),
            'linkedin' => trim($service->linkedin),
            'google' => trim($service->google),
            'pinterest' => trim($service->pinterest),
            'instagram' => trim($service->instagram),
            'snapchat' => trim($service->snapchat),
            'dribble' => trim($service->dribble),
            'behance' => trim($service->behance),
            'logo' => trim($service->logo),
            'banner' => trim($service->banner),
            'business_hours' => $service->business_hours,
            'area_of_service' => trim($service->areas_of_service),
            'max_km' => trim($service->max_km),
            'price_estimate' => $service->price_estimate,
            'price_extras' => $service->price_extras,
            'standard_response' => trim($service->standard_response),
            'creation_date' => $service->created_at->toDateTimeString()
        ];
    }
    public function includefaq(Service $service)
    {
        if (!$service->faqAnswers) {
            return null;
        }
        return $this->collection($service->faqAnswers, App::make(App\Http\Transformers\FaqTransformer::class));
    }
}