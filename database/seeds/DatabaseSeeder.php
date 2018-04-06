<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(citySeeder::class);
        $this->call(userSeeder::class);
        $this->call(serviceSeeder::class);
        $this->call(categorySeeder::class);
        $this->call(subcategorySeeder::class);
        $this->call(faqSeeder::class);
    }
}
