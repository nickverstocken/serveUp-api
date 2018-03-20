<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class City
 * 
 * @property int $id
 * @property string $name
 * @property int $zip
 * @property string $province
 * 
 * @property \Illuminate\Database\Eloquent\Collection $services
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App
 */
class City extends Model
{

	protected $table = 'city';
	public $timestamps = false;

	protected $casts = [
		'zip' => 'int'
	];

	protected $fillable = [
		'name',
		'zip',
		'province'
	];

	public function services()
	{
		return $this->hasMany(\App\Service::class);
	}

	public function users()
	{
		return $this->hasMany(\App\User::class);
	}
}
