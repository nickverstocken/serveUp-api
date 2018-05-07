<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
/**
 * Class Image
 *
 * @property int $id
 * @property int $message_id
 * @property string $message_type
 * @property string $message
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $type
 * @property int $media_id
 * @property \Carbon\Carbon $read_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\User $sender
 * @property \App\User $receiver
 * @property \App\Image $media
 *
 * @package App
 */

class Message extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $casts = [
        'message_id' => 'int',
        'sender_id' => 'int',
        'receiver_id' => 'int',
        'media_id' => 'int'
    ];
    protected $fillable = [
        'message_id',
        'message_type',
        'message',
        'sender_id',
        'receiver_id',
        'type',
        'media_id',
        'read_at'
    ];
    public function offer(){
        return $this->morphedByMany('App\Offer', 'message');
    }
    public function sender(){
        return $this->belongsTo(\App\User::class, 'sender_id');
    }
    public function receiver(){
        return $this->belongsTo(\App\User::class, 'receiver_id');
    }
}
