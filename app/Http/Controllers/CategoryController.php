<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Transformers\SubCategoryTransformer;
use App\SubCategory;
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
    private $subcategoryTransformer;
    function __construct(Manager $fractal, CategoryTranformer $categoryTransformer, SubCategoryTransformer $subCategoryTransformer)
    {
        $this->fractal = $fractal;
        $this->categoryTransformer = $categoryTransformer;
        $this->subcategoryTransformer = $subCategoryTransformer;
        $this->fractal->setSerializer(new ArraySerializer());
    }
    public function index(Request $request){
        $query = Category::get();
        $categories = new Collection($query, $this->categoryTransformer);
        $categories = $this->fractal->createData($categories);
        $this->fractal->parseIncludes($request->get('include', ''));
        $categories = $categories->toArray();
        return ApiResponseHelper::success(['categories' => $categories]);
    }
    public function getSubCategories(Request $request){
        $search_term = $request->input('search');
        $limit = $request->input('limit', 5);
        $query = SubCategory::hydrate(Searchy::sub_categories('name')->query($search_term)->getQuery()->limit($limit)->get()->toArray());
        $subcats = new Collection($query, $this->subcategoryTransformer);
        $this->fractal->parseIncludes(['category']);
        $subcats = $this->fractal->createData($subcats);
        $subcats = $subcats->toArray();
        return ApiResponseHelper::success(['subcategories' => $subcats]);
    }
    public function getSubCategory(Request $request, $id){
        $query = SubCategory::find($id);
        if(!$query){
            return ApiResponseHelper::error('Subcategory does not exist', 404);
        }
        $subcat = new Item($query, $this->subcategoryTransformer);
        $this->fractal->parseIncludes(['category']);
        $subcat = $this->fractal->createData($subcat);
        $subcat = $subcat->toArray();
        return ApiResponseHelper::success(['subcategory' => $subcat]);
    }
}
