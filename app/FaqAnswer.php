<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * Class FaqAnswer
 *
 * @property int $id
 * @property string $answer
 * @property int $service_id
 * @property int $faq_question_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Service $service
 * @property \App\FaqQuestion $faqQuestion
 *
 * @package App
 */
class FaqAnswer extends Model
{
    protected $fillable = [
        'answer',
        'service_id',
        'faq_question_id',
    ];
    public function service()
    {
        return $this->belongsTo(\App\Service::class);
    }
    public function faqQuestion()
    {
        return $this->belongsTo(\App\FaqQuestion::class);
    }
}
