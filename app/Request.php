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
 * Class Request
 * 
 * @property int $id
 * @property string $title
 * @property int $user_id
 * @property string $description
 * @property \Carbon\Carbon $due_date
 * @property float $budget
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\User $user
 * @property \Illuminate\Database\Eloquent\Collection $offers
 *
 * @package App\Models
 */
class Request extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'user_id' => 'int',
		'budget' => 'float'
	];

	protected $dates = [
		'due_date'
	];

	protected $fillable = [
		'title',
		'user_id',
		'description',
		'due_date',
		'budget'
	];

	public function user()
	{
		return $this->belongsTo(\App\User::class);
	}

	public function offers()
	{
		return $this->hasMany(\App\Offer::class);
	}
}
