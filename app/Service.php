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
 * @property int $category_id
 * @property string $address
 * @property int $city_id
 * @property string $country
 * @property string $tel
 * @property int $experience
 * @property string $website
 * @property string $social_networks
 * @property string $logo
 * @property string $banner
 * @property string $business_hours
 * @property string $areas_of_service
 * @property int $max_km
 * @property float $price_estimate
 * @property string $rate
 * @property string $price_extras
 * @property string $faq
 * @property string $standard_response
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\User $user
 * @property \App\City $city
 * @property \App\Category $category
 * @property \App\Category $subcategory
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
        'areas_of_service' => 'array',
        'price_extras' => 'array',
        'social_networks' => 'array',
        'faq' => 'array'
	];

	protected $fillable = [
		'name',
        'user_id',
		'description',
        'subcategory_id',
        'category_id',
        'address',
		'city_id',
		'country',
        'tel',
        'experience',
        'website',
        'social_networks',
		'logo',
		'banner',
		'business_hours',
		'areas_of_service',
		'max_km',
		'price_estimate',
		'rate',
		'price_extras',
        'faq',
		'standard_response'
	];
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($service) {
            foreach ($service->offers()->get() as $offer) {
                $offer->delete();
            }
            $service->reviews()->delete();
        });
    }
	public function city()
	{
		return $this->belongsTo(\App\City::class);
	}
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
	public function category()
	{
		return $this->belongsTo(\App\Category::class);
	}
    public function subcategory()
    {
        return $this->belongsTo(\App\SubCategory::class);
    }
	public function offers()
	{
		return $this->hasMany(\App\Offer::class);
	}
    public function images(){
        return $this->morphMany('App\Image', 'image' );
    }

    public function reviews(){
        return $this->morphMany('App\Review', 'review' );
    }

    public function tags(){
        return $this->morphToMany('App\Tag', 'taggable');
    }
}
