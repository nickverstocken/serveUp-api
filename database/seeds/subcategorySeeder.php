<?php

use Illuminate\Database\Seeder;
use App\SubCategory;
class subcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sub_categories = array(
            array(
                'category_id' => 1,
                'name' => 'Poetshulp',
                'plural' => 'Poetshulpen'
            ),
            array(
                'category_id' => 1,
                'name' => 'Tuin onderhoud',
                'plural' => 'Tuiniers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Tuin aanleg',
                'plural' => 'Tuin aanleggers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Diverse klusjes',
                'plural' => 'Klusjesmannen'
            ),
            array(
                'category_id' => 1,
                'name' => 'Interieur ontwerp',
                'plural' => 'Interieur designers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Architect',
                'plural' => 'Architecten'
            ),
            array(
                'category_id' => 1,
                'name' => 'Binnenhuis architect',
                'plural' => 'Binnenhuis architecten'
            ),
            array(
                'category_id' => 1,
                'name' => 'Aanbesteding',
                'plural' => 'Aanbesteders'
            ),
            array(
                'category_id' => 1,
                'name' => 'Dakwerken',
                'plural' => 'Dakwerkers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Schilderen',
                'plural' => 'Schilders'
            ),
            array(
                'category_id' => 1,
                'name' => 'Tapijt schoonmaak',
                'plural' => 'Tapijt schoonmakers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Elektriciën',
                'plural' => 'Elektriciëns'
            ),
            array(
                'category_id' => 1,
                'name' => 'Slotenmaker',
                'plural' => 'Slotenmakers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Loodgieter',
                'plural' => 'Loodgieters'
            ),
            array(
                'category_id' => 1,
                'name' => 'Metser',
                'plural' => 'Metsers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Zonnenpanelen',
                'plural' => 'Zonnepaneel experten'
            ),
            array(
                'category_id' => 1,
                'name' => 'Isolatiespecialist',
                'plural' => 'Isolatiespecialisten'
            ),
            array(
                'category_id' => 1,
                'name' => 'Terras aanleg',
                'plural' => 'Terras aanleggers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Veranda aanleg',
                'plural' => 'Verranda aanleggers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Garages en carports',
                'plural' => 'Garage experten'
            ),
            array(
                'category_id' => 1,
                'name' => 'Vloegwerken',
                'plural' => 'Vloerleggers'
            ),
            array(
                'category_id' => 1,
                'name' => 'Schrijnwerkerij',
                'plural' => 'Schrijnwerkijen'
            ),
            array(
                'category_id' => 1,
                'name' => 'Domotica',
                'plural' => 'Domotica specialisten'
            ),
            array(
                'category_id' => 2,
                'name' => 'DJ',
                'plural' => "DJ's"
            ),
            array(
                'category_id' => 2,
                'name' => 'Muziek apparatuur verhuur',
                'plural' => 'Muziek apparatuur verhuurders'
            ),
            array(
                'category_id' => 2,
                'name' => 'Licht en geluid',
                'plural' => 'Licht en geluidsspecialisten'
            ),
            array(
                'category_id' => 2,
                'name' => 'Catering',
                'plural' => 'Catering voorzieningen'
            ),
            array(
                'category_id' => 2,
                'name' => 'Schminker',
                'plural' => 'Schminkers'
            ),
            array(
                'category_id' => 2,
                'name' => 'Barmedewerker',
                'plural' => 'Barmedewerkers'
            ),
            array(
                'category_id' => 2,
                'name' => 'Huwelijks bloemist',
                'plural' => 'Bloemisten'
            ),
            array(
                'category_id' => 2,
                'name' => 'Goochelaar',
                'plural' => 'Goochelaars'
            ),
            array(
                'category_id' => 2,
                'name' => 'Evenement fotografie',
                'plural' => 'Evenement fotografen'
            ),
            array(
                'category_id' => 2,
                'name' => 'Party planner',
                'plural' => 'Party planners'
            ),
            array(
                'category_id' => 3,
                'name' => 'Zanglessen',
                'plural' => 'Zang instructeurs'
            ),
            array(
                'category_id' => 3,
                'name' => 'Piano lessen',
                'plural' => 'Piano instructeurs'
            ),
            array(
                'category_id' => 3,
                'name' => 'Zelfverdediging',
                'plural' => 'Zelfverdedigingsspecialisten'
            ),
            array(
                'category_id' => 3,
                'name' => 'Bijlessen',
                'plural' => 'Bijles gevers'
            ),
            array(
                'category_id' => 3,
                'name' => 'Dans lessen',
                'plural' => 'Dans instructeurs'
            ),
            array(
                'category_id' => 3,
                'name' => 'EHBO training',
                'plural' => 'EHBO trainers'
            ),
            array(
                'category_id' => 3,
                'name' => 'Acteer lessen',
                'plural' => 'Acteer instructeurs'
            ),
            array(
                'category_id' => 3,
                'name' => 'Gitaar lessen',
                'plural' => 'Gitaar leraars'
            ),
            array(
                'category_id' => 3,
                'name' => 'Tennis lessen',
                'plural' => 'Tennis leraars'
            ),
            array(
                'category_id' => 3,
                'name' => 'Fitness coach',
                'plural' => 'Fitness coaches'
            ),
            array(
                'category_id' => 3,
                'name' => 'Kook lessen',
                'plural' => 'Kook instructeurs'
            ),
            array(
                'category_id' => 3,
                'name' => 'Fotografie lessen',
                'plural' => 'Fotografie leraars'
            ),
            array(
                'category_id' => 4,
                'name' => 'Massage',
                'plural' => 'Masseurs'
            ),
            array(
                'category_id' => 4,
                'name' => 'Kinesist',
                'plural' => 'Kinesisten'
            ),
            array(
                'category_id' => 4,
                'name' => 'Yoga',
                'plural' => 'Yoga instructeurs'
            ),
            array(
                'category_id' => 4,
                'name' => 'Life coach',
                'plural' => 'Life coaches'
            ),
            array(
                'category_id' => 4,
                'name' => 'Diëtiste',
                'plural' => 'Diëtistes'
            ),
            array(
                'category_id' => 4,
                'name' => 'Kapper',
                'plural' => 'Kappers'
            ),
            array(
                'category_id' => 4,
                'name' => 'Make up',
                'plural' => 'Visagisten'
            ),
            array(
                'category_id' => 4,
                'name' => 'Nagelstyliste',
                'plural' => 'Nagelstylistes'
            ),
            array(
                'category_id' => 5,
                'name' => 'Financieel adviseur',
                'plural' => 'Financieel adviseurs'
            ),
            array(
                'category_id' => 5,
                'name' => 'Vertalingen',
                'plural' => 'Tolken'
            ),
            array(
                'category_id' => 5,
                'name' => 'Marketing',
                'plural' => 'Marketeers'
            ),
            array(
                'category_id' => 5,
                'name' => 'Boekhouder',
                'plural' => 'Boekhouders'
            ),
            array(
                'category_id' => 5,
                'name' => 'Bedrijfsadviseur',
                'plural' => 'Bedrijfsadviseurs'
            ),
            array(
                'category_id' => 5,
                'name' => 'Public relations',
                'plural' => 'PR specialisten'
            ),
            array(
                'category_id' => 6,
                'name' => 'Naaiwerken',
                'plural' => 'Naaispecialisten'
            ),
            array(
                'category_id' => 6,
                'name' => 'Breien',
                'plural' => 'Brei instructeurs'
            ),
            array(
                'category_id' => 6,
                'name' => 'Haken',
                'plural' => 'Haak instructeurs'
            ),
            array(
                'category_id' => 6,
                'name' => 'Portretteren',
                'plural' => 'Kunstenaars'
            ),
            array(
                'category_id' => 6,
                'name' => 'Songwriter',
                'plural' => 'Songwriters'
            ),
            array(
                'category_id' => 6,
                'name' => 'Potten bakker',
                'plural' => 'Potten bakkers'
            ),
            array(
                'category_id' => 6,
                'name' => 'Graveren',
                'plural' => 'Etsers'
            ),
            array(
                'category_id' => 6,
                'name' => 'Lederbewerking',
                'plural' => 'Lederbewerkers'
            ),
            array(
                'category_id' => 6,
                'name' => 'Houtbewerking',
                'plural' => 'Houtbewerkers'
            ),
            array(
                'category_id' => 7,
                'name' => 'Web design',
                'plural' => 'Web designers'
            ),
            array(
                'category_id' => 7,
                'name' => 'Grafisch design',
                'plural' => 'Grafisch designers'
            ),
            array(
                'category_id' => 7,
                'name' => 'Logo ontwerp',
                'plural' => 'Logo ontwerpers'
            ),
            array(
                'category_id' => 7,
                'name' => 'UI/UX ontwerp',
                'plural' => 'UI/UX designers'
            ),
            array(
                'category_id' => 7,
                'name' => 'Mobiele applicatie ontwikkelaar',
                'plural' => 'App ontwikkelaars'
            ),
            array(
                'category_id' => 7,
                'name' => 'Software ontwikkelaar',
                'plural' => 'Software ontwikkelaars'
            ),
            array(
                'category_id' => 7,
                'name' => '3D ontwerp',
                'plural' => '3D designers'
            ),
            array(
                'category_id' => 7,
                'name' => 'Regisseur',
                'plural' => 'Regisseurs'
            ),
            array(
                'category_id' => 7,
                'name' => 'Video editting',
                'plural' => 'Video edittors'
            ),
            array(
                'category_id' => 7,
                'name' => 'SEO & Online marketing',
                'plural' => 'Online marketeers'
            ),
            array(
                'category_id' => 7,
                'name' => 'Social media expert',
                'plural' => 'Social media experten'
            ),
            array(
                'category_id' => 8,
                'name' => 'Echtscheiding advocaat',
                'plural' => 'Echtscheidings advocaten'
            ),
            array(
                'category_id' => 8,
                'name' => 'Auteursrecht advocaat',
                'plural' => 'Auteursrecht advocaten'
            ),
            array(
                'category_id' => 8,
                'name' => 'Consumenten bescherming',
                'plural' => 'Onbudsdiensten'
            ),
            array(
                'category_id' => 8,
                'name' => 'Ondernemingsrecht advocaat',
                'plural' => 'Ondernemingsrecht advocaten'
            ),
            array(
                'category_id' => 8,
                'name' => 'Juridisch adviseur',
                'plural' => 'Juridisch adviseurs'
            ),
            array(
                'category_id' => 9,
                'name' => 'Hondenuitlater',
                'plural' => 'Hondenuitlaters'
            ),
            array(
                'category_id' => 9,
                'name' => 'Hondenopvang',
                'plural' => 'Honden opvang centra'
            ),
            array(
                'category_id' => 9,
                'name' => 'Aquarium diensten',
                'plural' => 'Aquarium diensten'
            ),
            array(
                'category_id' => 9,
                'name' => 'Huisdieren oppas',
                'plural' => 'Pet sitters'
            ),
            array(
                'category_id' => 9,
                'name' => 'Hondenkapper',
                'plural' => 'Hondenkappers'
            ),
            array(
                'category_id' => 9,
                'name' => 'Katten kapper',
                'plural' => 'Katten kappers'
            ),
            array(
                'category_id' => 9,
                'name' => 'Dieren transport',
                'plural' => 'Dieren transporteurs'
            ),
            array(
                'category_id' => 9,
                'name' => 'Huisdier training',
                'plural' => 'Huisdier trainers'
            ),
            array(
                'category_id' => 9,
                'name' => 'Katten opvang',
                'plural' => 'Katten opvang centra'
            ),
            array(
                'category_id' => 9,
                'name' => 'Dieren opvang',
                'plural' => 'Dieren opvang centra'
            ),
            array(
                'category_id' => 10,
                'name' => 'Luchtfotografie',
                'plural' => 'Luchtfotografen'
            ),
            array(
                'category_id' => 10,
                'name' => 'Droneverhuur',
                'plural' => 'Drone verhuurders'
            ),
            array(
                'category_id' => 10,
                'name' => 'Huwelijks fotografie',
                'plural' => 'Huwelijks fotografen'
            ),
            array(
                'category_id' => 10,
                'name' => 'Portret fotografie',
                'plural' => 'Portret fotografen'
            ),
            array(
                'category_id' => 10,
                'name' => 'Evenement fotografie',
                'plural' => 'Event fotografen'
            ),
            array(
                'category_id' => 10,
                'name' => 'Huisdier fotografie',
                'plural' => 'Huisdier fotografen'
            ),
            array(
                'category_id' => 10,
                'name' => 'Bedrijfs fotografie',
                'plural' => 'Bedrijfs fotografen'
            ),
            array(
                'category_id' => 11,
                'name' => 'Computerherstelling',
                'plural' => 'PC techniekers'
            ),
            array(
                'category_id' => 11,
                'name' => 'Smartphone herstelling',
                'plural' => 'Smartphone techniekers'
            ),
            array(
                'category_id' => 11,
                'name' => 'Data herstel',
                'plural' => 'Data recovery experten'
            ),
            array(
                'category_id' => 11,
                'name' => 'Netwerkenbeheer',
                'plural' => 'Netwerkbeheerders'
            ),
            array(
                'category_id' => 11,
                'name' => 'Printer herstelling',
                'plural' => 'Printer herstellers'
            ),
            array(
                'category_id' => 11,
                'name' => 'Plaatsing huishoudtoestellen',
                'plural' => 'Huishoudtoestellen installateurs'
            )
        );
        foreach ($sub_categories as $sub_category) {
           SubCategory::create($sub_category);
        }
    }
}
