<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Tag
 * 
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Taggable $taggable
 *
 * @package App\Models
 */
class Tag extends Model
{
	protected $fillable = [
		'name'
	];

	public function taggable()
	{
		return $this->hasOne(\App\Taggable::class);
	}
    public function services()
    {
        return $this->morphedByMany('App\Service', 'taggable');
    }
}
