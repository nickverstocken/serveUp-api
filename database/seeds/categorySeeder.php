<?php

use Illuminate\Database\Seeder;
use App\Category;
class categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        //
        $categories = array(
            array(
                'name' => 'Thuis',
                'picturePath' => 'default_images/categories/home'
            ),
            array(
                'name' => 'Evenementen',
                'picturePath' => 'default_images/categories/event'
            ),
            array(
                'name' => 'Lessen',
                'picturePath' => 'default_images/categories/lesson'
            ),
            array(
                'name' => 'Wellness',
                'picturePath' => 'default_images/categories/wellness'
            ),
            array(
                'name' => 'Zakelijk',
                'picturePath' => 'default_images/categories/business'
            ),
            array(
                'name' => 'Hobby',
                'picturePath' => 'default_images/categories/hobby'
            ),
            array(
                'name' => 'Multimedia',
                'picturePath' => 'default_images/categories/webdesign'
            ),
            array(
                'name' => 'Wettelijk',
                'picturePath' => 'default_images/categories/legal'
            ),
            array(
                'name' => 'Huisdieren',
                'picturePath' => 'default_images/categories/pet'
            ),
            array(
                'name' => 'Fotografie',
                'picturePath' => 'default_images/categories/photography'
            ),
            array(
                'name' => 'Technisch',
                'picturePath' => 'default_images/categories/techrepair'
            ),
            array(
                'name' => 'Persoonlijk',
                'picturePath' => 'default_images/categories/personal'
            )
        );
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
