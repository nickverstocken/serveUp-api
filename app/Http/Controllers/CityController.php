<?php

namespace App\Http\Controllers;

use App\City;
use App\Helpers\ApiResponseHelper;
use App\Http\Transformers\CityTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\Fractalistic\ArraySerializer;
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
}
