<?php

use Illuminate\Database\Seeder;
use App\FaqQuestion;
class faqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faq_questions')->delete();
        $questions = array(
            array(
                'question' => 'Hoe onderscheid jij je van de concurrentie?'
            ),
            array(
                'question' => 'Wat zit er allemaal in de prijs en wat niet?'
            ),
            array(
                'question' => 'Wat moet er allemaal voorzien zijn op de locatie zelf?'
            )
        );
        foreach ($questions as $question) {
            FaqQuestion::create($question);
        }
    }
}
