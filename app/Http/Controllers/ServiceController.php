<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FunctionsHelper;
use App\SubCategory;
use DB;
use App\Tag;
use App\Service;
use App\Taggable;
use App\City;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use App\Http\Helpers\ImageUpload;
use Spatie\Fractalistic\ArraySerializer;
use Validator;
use JWTAuth;
use League\Fractal\Manager;
use App\Http\Transformers\ServiceTransformer;
use League\Fractal\Resource\Item;
use Carbon\Carbon;

class ServiceController extends Controller
{
    private $fractal;
    private $serviceTransformer;

    function __construct(Manager $fractal, ServiceTransformer $serviceTransformer)
    {
        $this->fractal = $fractal;
        $this->serviceTransformer = $serviceTransformer;
        $this->fractal->setSerializer(new ArraySerializer());
    }

    public function index(Request $request)
    {

    }

    public function update(Request $request, $serviceId)
    {
        $service = Service::find($serviceId);
        $user = JWTAuth::parseToken()->toUser();
        if (!$service) {
            return ApiResponseHelper::error('service bestaat niet', 404);
        }
        if ($service->user_id != $user->id) {
            return ApiResponseHelper::error('service hoort niet bij jou');
        }
        $input = $request->all();
        $rules = [
            'name' => 'string|max:50',
            'description' => 'string',
            'address' => 'nullable|string|max:191',
            'tel' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:191'
        ];
        if ($request->hasFile('logo')) {
            $rules['logo'] = 'image|mimes:jpg,png,jpeg';
        }
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return ApiResponseHelper::error($validator->messages(), 422);
        }

        if ($request->hasFile('logo')) {
            $file = $input['logo'];
            $extension = $file->getClientOriginalExtension();
            $name = $service->user_id;
            $path = ImageUpload::saveImage($input['logo'], 150, 150, 'logo', $extension, $name . '/service/' . $service->id);
            $input['logo'] = $path . '?' . Carbon::now()->timestamp;
        } else {
            $input['logo'] = $service->logo;
        }
        if ($request->has('max_km')) {
            if ($service->max_km != $request->get('max_km')) {

               $service->areas_of_service = self::getCitiesWithinRadius($service->city->zip, $service->city->name, $request->get('max_km'));
            }
        }
        if ($request->has('tags')) {
            $tags = $request->json()->get('tags');
            foreach ($tags as $tag) {
                $this->saveTags($tag['name'], $service);
            }
        }
        $service->update($input);
        $service = new Item($service, $this->serviceTransformer);
        $service = $this->fractal->createData($service);
        $service = $service->toArray();
        return ApiResponseHelper::success(['service' => $service], 'service update success');
    }

    public function saveTags($name, $service)
    {
        $tagFind = Tag::where('name', $name)->get()->first();
        if (!$tagFind) {
            $tag = new Tag(['name' => $name]);
            $tag->save();
            $tag->services()->save($service);
        } else {
            if (!$service->tags()->where('id', $tagFind->id)->exists()) {
                $tag = $tagFind;
                $tag->services()->save($service);
            }
        }
    }

    public function removeTagFromService($serviceId, $tagName)
    {
        $service = Service::find($serviceId);
        $tagId = Tag::where('name', $tagName)->first();
        if ($tagId) {
            $tagId = $tagId->id;
        } else {
            return ApiResponseHelper::error('Tag bestaat niet', 404);
        }
        $user = JWTAuth::parseToken()->toUser();
        //->photos()->where('id', '=', 1)->delete();
        if (!$service) {
            return ApiResponseHelper::error('service bestaat niet', 404);
        }
        if ($service->user_id != $user->id) {
            return ApiResponseHelper::error('service hoort niet bij jou');
        }
        $taggable = Taggable::where('taggable_type', 'App\Service')->where('taggable_id', $serviceId)->where('tag_id', $tagId);
        $taggable->delete();
        return ApiResponseHelper::success([], 'Tag succesvol verwijderd!');
    }

    public function getCitiesWithinRadius($zip, $name, $radius = 5)
    {
        $city = City::where('zip', $zip)->where('name', 'like', $name . '%')->first();
        $zips = [];
        if ($city) {
/*            $sql = 'SELECT *, (6371* ACOS(
                    COS(RADIANS(' . $city->lat . '))
                    * COS( RADIANS(lat))
                    * COS( RADIANS(' . $city->lng . ')
                    - RADIANS(lng))
                    + SIN( RADIANS(' . $city->lat . '))
                    * SIN( RADIANS(lat))))
                    AS distance FROM city HAVING distance<=' . $radius . ' AND distance > 0';
            $result = DB::select($sql);*/
            $allcities = City::all();

            foreach ($allcities as $citydb) {
                if ((FunctionsHelper::vincentyGreatCircleDistance($city->lat, $city->lng, $citydb->lat, $citydb->lng) / 1000) <= $radius) {
                    $citydb->distance = FunctionsHelper::vincentyGreatCircleDistance($city->lat, $city->lng, $citydb->lat, $citydb->lng) / 1000;
                    $zips[] = [$citydb->zip => $citydb->name];
                }
            }
        }
        return $zips;
    }
    public function getServicesCountNearby(Request $request, $subcatId, $name){
       $services = SubCategory::find($subcatId)->services()->where('areas_of_service', 'like', '%' . $name . '%')->select('id')->get();
       Return ApiResponseHelper::success(['ids' => $services, 'count' => $services->count()]);
    }
}
