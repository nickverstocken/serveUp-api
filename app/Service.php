<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
/**
 * Class Service
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $city_id
 * @property string $country
 * @property string $logo
 * @property string $banner
 * @property string $business_hours
 * @property string $areas_of_service
 * @property int $max_km
 * @property float $price_estimate
 * @property string $rate
 * @property string $price_extras
 * @property string $standard_response
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\City $city
 * @property \Illuminate\Database\Eloquent\Collection $has_categories
 * @property \Illuminate\Database\Eloquent\Collection $has_services
 * @property \Illuminate\Database\Eloquent\Collection $offers
 * @property \Illuminate\Database\Eloquent\Collection $faqAnswers
 *
 * @package App\Models
 */
class Service extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'city_id' => 'int',
		'max_km' => 'int',
		'price_estimate' => 'float',
        'business_hours' => 'array',
        'price_extras' => 'array'
	];

	protected $fillable = [
		'name',
		'description',
		'city_id',
		'country',
		'logo',
		'banner',
		'business_hours',
		'areas_of_service',
		'max_km',
		'price_estimate',
		'rate',
		'price_extras',
		'standard_response'
	];

	public function city()
	{
		return $this->belongsTo(\App\City::class);
	}

	public function has_categories()
	{
		return $this->hasMany(\App\HasCategory::class);
	}

	public function has_services()
	{
		return $this->hasMany(\App\HasService::class);
	}

	public function offers()
	{
		return $this->hasMany(\App\Offer::class);
	}
    public function faqAnswers()
    {
        return $this->hasMany(\App\FaqAnswer::class);
    }
}
