<?php

use Illuminate\Database\Seeder;
use App\HasService;
class hasServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hasServices = array(
            array(
                'user_id' => 1,
                'service_id' => 1,
            ),
            array(
                'user_id' => 3,
                'service_id' => 2,
            ),
            array(
                'user_id' => 4,
                'service_id' => 3,
            ),
            array(
                'user_id' => 6,
                'service_id' => 4,
            )
        );
        foreach ($hasServices as $hasService) {
            HasService::create($hasService);
        }
    }
}
