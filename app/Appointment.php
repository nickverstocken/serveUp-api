<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Class Category
 *
 * @property int $id
 * @property string $title
 * @property \Carbon\Carbon $date
 * @property \Carbon\Carbon $time
 * @property string $location
 * @property bool $approved
 * @property int $creator_id
 * @property int $offer_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\User $creator
 * @property \App\Offer $offer
 * @property \Illuminate\Database\Eloquent\Collection $subcategories
 *
 * @package App
 */

class Appointment extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $casts = [
        'id' => 'int',
        'creator_id' => 'int',
        'offer_id' => 'int',
        'approved' => 'bool',
        'location' => 'array'
    ];
    protected $fillable = [
        'title',
        'date',
        'time',
        'location',
        'approved',
        'creator_id',
        'offer_id'
    ];
    public function creator()
    {
        return $this->belongsTo(\App\User::class);
    }
    public function offer()
    {
        return $this->belongsTo(\App\Offer::class);
    }
}
