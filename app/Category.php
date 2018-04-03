<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Category
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $picturePath
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $services
 *
 * @package App
 */
class Category extends Model
{

	protected $fillable = [
		'name',
		'description',
        'picturePath'
	];

	public function services()
	{
		return $this->hasMany(\App\Service::class);
	}
}
