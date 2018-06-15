<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 3/19/18
 * Time: 11:22 PM
 */

namespace App\Http\Transformers;
use App;
use App\Category;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;
class CategoryTranformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'subcategories'
    ];
    public function transform(Category $category)
    {

        return [
            'id' => $category->id,
            'name' => trim($category->name),
            'description' => trim($category->description),
            'picture' => $category->picturePath,
            'created_at' => $category->created_at->toDateTimeString(),
            'updated_at' => $category->updated_at->toDateTimeString()
        ];
    }
    public function includesubcategories(Category $category)
    {
        if (!$category->subcategories) {
            return null;
        }
        return $this->collection($category->subcategories, App::make(App\Http\Transformers\SubCategoryTransformer::class));
    }
}