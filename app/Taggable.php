<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Taggable
 * 
 * @property int $taggable_id
 * @property string $taggable_type
 * @property int $tag_id
 * 
 * @property \App\Tag $tag
 *
 * @package App\Models
 */
class Taggable extends Model
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'taggable_id' => 'int',
		'tag_id' => 'int'
	];

	protected $fillable = [
		'taggable_id',
		'taggable_type',
		'tag_id'
	];

	public function tag()
	{
		return $this->belongsTo(\App\Tag::class);
	}
}
