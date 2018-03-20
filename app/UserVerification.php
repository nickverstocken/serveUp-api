<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class UserVerification
 * 
 * @property int $id
 * @property int $user_id
 * @property string $token
 * 
 * @property \App\User $user
 *
 * @package App\Models
 */
class UserVerification extends Model
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'user_id',
		'token'
	];

	public function user()
	{
		return $this->belongsTo(\App\User::class);
	}
}
