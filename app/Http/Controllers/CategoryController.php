<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use App\Http\Transformers\CategoryTranformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Spatie\Fractalistic\ArraySerializer;
use TomLingham\Searchy\Facades\Searchy;

class CategoryController extends Controller
{
    private $fractal;
    private $categoryTransformer;
    function __construct(Manager $fractal, CategoryTranformer $categoryTransformer)
    {
        $this->fractal = $fractal;
        $this->categoryTransformer = $categoryTransformer;
        $this->fractal->setSerializer(new ArraySerializer());
    }
    public function index(){
        $query = Category::get();
        $categories = new Collection($query, $this->categoryTransformer);
        $categories = $this->fractal->createData($categories);
        $categories = $categories->toArray();
        return ApiResponseHelper::success(['categories' => $categories]);
    }
    public function getSubCategories(Request $request){
        $search_term = $request->input('search');
        $limit = $request->input('limit', 5);
        $subcats = Searchy::sub_categories('name')->query($search_term)->get();
        return ApiResponseHelper::success(['subcategories' => $subcats]);
    }
}
