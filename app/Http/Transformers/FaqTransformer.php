<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 3/20/18
 * Time: 11:54 PM
 */

namespace App\Http\Transformers;
use App;
use App\FaqAnswer;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class FaqTransformer extends TransformerAbstract
{
    public function transform(FaqAnswer $answer)
    {

        return [
            'id' => $answer->id,
            'question_id' => $answer->faq_question_id,
            'question' => $answer->faqQuestion->question,
            'answer' => $answer->answer
        ];
    }
}