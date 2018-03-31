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
 * @property string $address
 * @property int $city_id
 * @property string $country
 * @property string $tel
 * @property int $experience
 * @property string $website
 * @property string $facebook
 * @property string $youtube
 * @property string $twitter
 * @property string $linkedin
 * @property string $google
 * @property string $pinterest
 * @property string $instagram
 * @property string $snapchat
 * @property string $dribble
 * @property string $behance
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
 * @property \App\User $user
 * @property \App\City $city
 * @property \Illuminate\Database\Eloquent\Collection $has_categories
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
        'address',
		'city_id',
		'country',
        'tel',
        'experience',
        'website',
        'facebook',
        'youtube',
        'twitter',
        'linkedin',
        'google',
        'pinterest',
        'instagram',
        'snapchat',
        'dribble',
        'behance',
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
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
	public function has_categories()
	{
		return $this->hasMany(\App\HasCategory::class);
	}
	public function offers()
	{
		return $this->hasMany(\App\Offer::class);
	}
    public function faqAnswers()
    {
        return $this->hasMany(\App\FaqAnswer::class);
    }
    public function images(){
        return $this->morphMany('App\Image', 'image' );
    }
}
