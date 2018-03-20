<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class HasCategory
 * 
 * @property int $id
 * @property int $service_id
 * @property int $category_id
 * 
 * @property \App\Category $category
 * @property \App\Service $service
 *
 * @package App
 */
class HasCategory extends Model
{
	public $timestamps = false;

	protected $casts = [
		'service_id' => 'int',
		'category_id' => 'int'
	];

	protected $fillable = [
		'service_id',
		'category_id'
	];

	public function category()
	{
		return $this->belongsTo(\App\Category::class);
	}

	public function service()
	{
		return $this->belongsTo(\App\Service::class);
	}
}
