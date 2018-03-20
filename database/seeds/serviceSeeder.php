<?php

use Illuminate\Database\Seeder;
use App\Service;
class serviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->delete();
        //
        $services = array(
            array(
                'name' => 'Solar Cleaning Services',
                'description' => 'Uitmunten als bedrijf door grondige vakkennis te koppelen aan klantgerichte dienstverlening. Deze basisgedachte wordt door SCS reeds meer dan 20 jaar toegepast en heeft ervoor gezorgd dat wij in een brede regio rond Sint-Niklaas langzaam maar zeker zijn uitgegroeid tot een begrip op gebied van schoonmaak: een KMO met oplossingen op maat voor KMO\'s.',
                'address' => 'Passtraat 71',
                'city_id' => 2234,
                'business_hours' => '["maandag"=>"09:00-17:00", "dinsdag"=>"09:00-17:00", "woensdag"=>"09:00-17:00", "donderdag"=>"", "vrijdag"=>"09:00-12:00", "zaterdag"=>"", "zondag"=>""]',
                'max_km' => 80,
                'price_estimate' => 35,
                'rate' => 'uur',
                'price_extras' => '["ramen"=>"€ 15", "plafond"=>"€ 20"]'
            ),
            array(
                'name' => 'Sani Joris',
                'description' => 'Een oerdegelijke loodgieter nodig in de regio Antwerpen voor uw sanitair? Ze zijn zeldzaam, maar ze bestaan! Loodgieter Sani Joris verzorgt de levering, installatie en onderhoud van uw centrale verwarming en sanitair!',
                'address' => 'Boerenkrijgplein 6',
                'city_id' => 1543,
                'business_hours' => '["maandag"=>"09:00-17:00", "dinsdag"=>"09:00-17:00", "woensdag"=>"09:00-17:00", "donderdag"=>"09:00-19:00", "vrijdag"=>"09:00-12:00", "zaterdag"=>"12:00-15:00", "zondag"=>""]',
                'max_km' => 50,
                'price_estimate' => 60,
                'rate' => 'uur'
            ),
            array(
                'name' => 'Discobar All Music',
                'description' => 'Discobar All Music zorgt voor de gepaste muziek op uw Evenementen, Huwelijksfeesten, Fuiven, Bals, Verjaardagsfeestjes en Recepties. Daarnaast kan u bij ons ook steeds terecht voor het huren van Licht- en geluidsinstallaties en Juke-boxen. Indien u vragen heeft, aarzel dan zeker niet om ons te contacteren.',
                'address' => 'Morkhovenseweg 1',
                'city_id' => 2664,
                'business_hours' =>'["maandag"=>"", "dinsdag"=>"", "woensdag"=>"22:00-05:00", "donderdag"=>"22:00-05:00", "vrijdag"=>"22:00-05:00", "zaterdag"=>"22:00-05:00", "zondag"=>"22:00-05:00"]',
                'price_estimate' => 150,
                'max_km' => 30,
                'rate' => '4 uur',
                'price_extras' => '["geluidsinstallate"=>"€ 200", "monitors"=>"€ 500"]'
            ),
            array(
                'name' => 'DJMarino',
                'description' => 'Discobar All Music zorgt voor de gepaste muziek op uw Evenementen, Huwelijksfeesten, Fuiven, Bals, Verjaardagsfeestjes en Recepties. Daarnaast kan u bij ons ook steeds terecht voor het huren van Licht- en geluidsinstallaties en Juke-boxen. Indien u vragen heeft, aarzel dan zeker niet om ons te contacteren.',
                'address' => 'Morkhovenseweg 1',
                'city_id' => 2664,
                'business_hours' => '["maandag"=>"", "dinsdag"=>"", "woensdag"=>"22:00-05:00", "donderdag"=>"22:00-05:00", "vrijdag"=>"22:00-05:00", "zaterdag"=>"22:00-05:00", "zondag"=>"22:00-05:00"]',
                'price_estimate' => 150,
                'max_km' => 200,
                'rate' => '4 uur',
                'price_extras' => '["geluidsinstallate"=>"€ 200", "monitors"=>"€ 500"]'
            )
        );
        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
