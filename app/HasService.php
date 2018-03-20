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
 * Class HasService
 * 
 * @property int $id
 * @property int $user_id
 * @property int $service_id
 * 
 * @property \App\Service $service
 * @property \App\User $user
 *
 * @package App
 */
class HasService extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'service_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'service_id'
	];

	public function service()
	{
		return $this->belongsTo(\App\Service::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class);
	}
}
