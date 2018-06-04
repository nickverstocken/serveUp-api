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
 * Class Offer
 * 
 * @property int $id
 * @property bool $accepted
 * @property float $price_offer
 * @property string $rate
 * @property string $status
 * @property int $request_id
 * @property int $service_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Request $request
 * @property \App\Service $service
 * @property \Illuminate\Database\Eloquent\Collection $appointments
 *
 * @package App
 */
class Offer extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'accepted' => 'bool',
		'price_offer' => 'float',
		'request_id' => 'int',
		'service_id' => 'int'
	];

	protected $fillable = [
		'accepted',
		'price_offer',
		'rate',
        'status',
		'request_id',
		'service_id'
	];

	public function request()
	{
		return $this->belongsTo(\App\Request::class);
	}

	public function service()
	{
		return $this->belongsTo(\App\Service::class);
	}
    public function messages(){
        return $this->morphMany('App\Message', 'message' );
    }
    public function appointments(){
        return $this->hasMany(\App\Appointment::class);
    }
    public function latestMessage()
    {
        return $this->morphOne('\App\Message', 'message')->latest();
    }
    public function images(){
        return $this->morphMany('App\Image', 'image' );
    }
}
