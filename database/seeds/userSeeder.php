<?php

use Illuminate\Database\Seeder;
use App\User;
class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        //
        $users = array(
            array(
                'fname' => 'Nick',
                'name' => 'Verstocken',
                'email' => 'nick@gmail.com',
                'password' => Hash::make('test123'),
                'address' => 'Kruisstraat 60',
                'city_id' => 2655,
                'role' => 'service',
                'is_verified' => true
            ),
            array(
                'fname' => 'Bart',
                'name' => 'Van Poecke',
                'email' => 'bart@gmail.com',
                'password' => Hash::make('test123'),
                'city_id' => 2655,
                'role' => 'user',
                'is_verified' => true
            ),
            array(
                'fname' => 'Eveline',
                'name' => 'Verhoeven',
                'email' => 'eveline@gmail.com',
                'address' => 'Kruisstraat 60',
                'password' => Hash::make('test123'),
                'city_id' => 2655,
                'role' => 'service',
                'is_verified' => true
            ),
            array(
                'fname' => 'Simon',
                'name' => 'Cerfontaine',
                'email' => 'simon@gmail.com',
                'password' => Hash::make('test123'),
                'city_id' => 2655,
                'role' => 'user',
                'is_verified' => true
            ),
            array(
                'fname' => 'Jens',
                'name' => 'Van Cleuvenbergen',
                'email' => 'jens@gmail.com',
                'password' => Hash::make('test123'),
                'city_id' => 2655,
                'role' => 'user',
                'is_verified' => true
            ),
            array(
                'fname' => 'Ann',
                'name' => 'Van Der Linden',
                'email' => 'ann@gmail.com',
                'password' => Hash::make('test123'),
                'city_id' => 2655,
                'role' => 'user',
                'is_verified' => true
            )
        );
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
