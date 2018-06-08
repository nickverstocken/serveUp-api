<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 18 Feb 2018 12:37:50 +0000.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Review
 * 
 * @property int $id
 * @property int $review_id
 * @property string $review_type
 * @property int $user_id
 * @property string $title
 * @property string $comment
 * @property int $score
 * @property string $path_thumb
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\User $user
 *
 * @package App\Models
 */
class Review extends Model
{

	protected $casts = [
		'review_id' => 'int',
		'user_id' => 'int',
		'score' => 'int'
	];

	protected $fillable = [
		'review_id',
		'review_type',
		'user_id',
		'title',
		'comment',
		'score',
		'path_thumb'
	];

	public function fromuser()
	{
		return $this->belongsTo(\App\User::class, 'user_id');
	}
    public function service(){
        return $this->morphedByMany('App\Service', 'review');
    }
    public function user(){
        return $this->morphedByMany('App\User', 'review');
    }
}
