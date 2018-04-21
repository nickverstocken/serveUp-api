<?php

use Illuminate\Database\Seeder;
use App\City;
class citySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('city')->delete();
        $file = public_path() . '/data/cities.json';
        $json = File::get($file);
        $data = json_decode($json, true);
        foreach ($data as $obj) {
            if($obj['lat']){
                City::create($obj);
            }
        }
    }
}
