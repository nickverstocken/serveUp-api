<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class FaqQuestion
 *
 * @property int $id
 * @property string $question
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $faqAnswers
 * @package App
 */
class FaqQuestion extends Model
{
    protected $fillable = [
        'question'
    ];
    public function faqAnswers()
    {
        return $this->hasMany(\App\FaqAnswer::class);
    }
}
