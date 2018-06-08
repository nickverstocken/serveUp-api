<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
/**
 * Class User
 * 
 * @property int $id
 * @property string $fname
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $address
 * @property int $city_id
 * @property string $country
 * @property string $picture
 * @property string $picture_thumb
 * @property string $introduction
 * @property string $role
 * @property bool $is_verified
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\City $city
 * @property \Illuminate\Database\Eloquent\Collection $services
 * @property \Illuminate\Database\Eloquent\Collection $requests
 * @property \Illuminate\Database\Eloquent\Collection $reviews
 * @property \Illuminate\Database\Eloquent\Collection $user_verifications
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'city_id' => 'int',
		'is_verified' => 'bool'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'fname',
		'name',
		'email',
		'password',
        'address',
		'city_id',
		'country',
		'picture',
		'picture_thumb',
		'introduction',
		'role',
		'is_verified',
		'remember_token'
	];

	public function city()
	{
		return $this->belongsTo(\App\City::class);
	}

	public function services()
	{
		return $this->hasMany(\App\Service::class);
	}

	public function requests()
	{
		return $this->hasMany(\App\Request::class);
	}

	public function has_reviews()
	{
		return $this->hasMany(\App\Review::class);
	}
    public function reviews(){
        return $this->morphMany('App\Review', 'review' );
    }
	public function user_verifications()
	{
		return $this->hasMany(\App\UserVerification::class);
	}
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
