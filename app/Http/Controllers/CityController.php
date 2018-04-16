<?php

namespace App\Http\Controllers;
use DB;
use App\City;
use App\Helpers\ApiResponseHelper;
use App\Http\Transformers\CityTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\Fractalistic\ArraySerializer;
use Illuminate\Support\Facades\Storage;
use File;
class CityController extends Controller
{
    private $fractal;
    private $cityTransformer;
    function __construct(Manager $fractal, CityTransformer $cityTransformer)
    {
        $this->fractal = $fractal;
        $this->cityTransformer = $cityTransformer;
        $this->fractal->setSerializer(new ArraySerializer());
    }
    public function search(Request $request){
        $search_term = $request->input('search');
        $query = City::query();
        $query = $query->where('name', 'LIKE', "%$search_term%")->orWhere('zip', 'LIKE', "$search_term%");
        $query = $query->get();
        $city = new Collection($query, $this->cityTransformer);
        $city = $this->fractal->createData($city);
        $city = $city->toArray();
        return ApiResponseHelper::success(['city' => $city]);
    }
    public function updateCities(){
        $cities = City::all();
        foreach($cities as $city){
           // dd($city->zip);
            $url = urldecode("https://maps.googleapis.com/maps/api/geocode/json?address=". $city->zip ."," . $city->name . ",België&key=AIzaSyAoSQ1d_q1zShvfks5KP5UQ5cWsj7muOwU&language=nl");
            dd($url);
            $file = file_get_contents($url);

            $data = json_decode($file, true);

           // $province = $data['results'][0]['address_components'][2]['long_name'];
            if(isset($data['results'][0])){
                $lat = $data['results'][0]['geometry']['location']['lat'];
                $lng =  $data['results'][0]['geometry']['location']['lng'];
                var_dump($lat);
                var_dump($lng);
            }else{
                var_dump('error for ' . $city->name  . ', ' . $city->zip);
            }

           // var_dump($province);
        }

    }
}
