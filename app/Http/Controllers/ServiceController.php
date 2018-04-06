<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Service;
use App\Taggable;
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
    public function index(Request $request){

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
        if(!$tagFind){
            $tag = new Tag(['name' => $name]);
            $tag->save();
            $tag->services()->save($service);
        }else{
            if(!$service->tags()->where('id', $tagFind->id)->exists()){
                $tag = $tagFind;
                $tag->services()->save($service);
            }
        }
    }
    public function removeTagFromService(Request $request, $serviceId, $tagName){
        $service = Service::find($serviceId);
        $tagId = Tag::where('name', $tagName)->first();
        if($tagId){
            $tagId = $tagId->id;
        }else{
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
}
