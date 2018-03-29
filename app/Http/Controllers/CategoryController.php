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

use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
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
}
