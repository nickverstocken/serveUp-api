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
 * Class Image
 * 
 * @property int $id
 * @property int $image_id
 * @property string $image_type
 * @property string $name
 * @property string $path
 * @property string $path_thumb
 * @property string $mime_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App
 */
class Image extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'image_id' => 'int'
	];

	protected $fillable = [
		'image_id',
		'image_type',
		'name',
		'path',
		'path_thumb',
		'mime_type'
	];
    public function service(){
        return $this->morphedByMany('App\Service', 'image');
    }
}
