<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 3/20/18
 * Time: 11:54 PM
 */

namespace App\Http\Transformers;
use App;
use App\Appointment;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class FullCalendarTransformer extends TransformerAbstract
{
    public function transform(Appointment $appointment)
    {

        return [
            'id' => $appointment->id,
            'start' => $appointment->date . 'T' . $appointment->time,
            'title' => $appointment->title
        ];
    }
}