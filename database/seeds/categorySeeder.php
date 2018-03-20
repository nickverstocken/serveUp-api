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
                'picturePath' => 'default_images/categories/home.jpg'
            ),
            array(
                'name' => 'Evenementen',
                'picturePath' => 'default_images/categories/event.jpg'
            ),
            array(
                'name' => 'Lessen',
                'picturePath' => 'default_images/categories/lesson.jpg'
            ),
            array(
                'name' => 'Wellness',
                'picturePath' => 'default_images/categories/wellness.jpg'
            ),
            array(
                'name' => 'Zakelijk',
                'picturePath' => 'default_images/categories/business.jpg'
            ),
            array(
                'name' => 'Hobby',
                'picturePath' => 'default_images/categories/hobby.jpg'
            ),
            array(
                'name' => 'Web/Design',
                'picturePath' => 'default_images/categories/webdesign.jpg'
            ),
            array(
                'name' => 'Wettelijk',
                'picturePath' => 'default_images/categories/legal.jpg'
            ),
            array(
                'name' => 'Huisdieren',
                'picturePath' => 'default_images/categories/pet.jpg'
            ),
            array(
                'name' => 'Fotografie',
                'picturePath' => 'default_images/categories/photography.jpg'
            ),
            array(
                'name' => 'Technisch',
                'picturePath' => 'default_images/categories/techrepair.jpg'
            ),
            array(
                'name' => 'Persoonlijk',
                'picturePath' => 'default_images/categories/personal.jpg'
            )
        );
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
