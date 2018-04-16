<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 4/7/18
 * Time: 7:59 PM
 */

namespace App\Http\Transformers;
use App;
use App\SubCategory;
use League\Fractal\TransformerAbstract;
class SubCategoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'category'
    ];
    public function transform(SubCategory $subcat)
    {

        return [
            'id' => $subcat->id,
            'name' => trim($subcat->name),
            'plural' => $subcat->plural,
            'description' => trim($subcat->description),
            'created_at' => $subcat->created_at->toDateTimeString(),
            'updated_at' => $subcat->updated_at->toDateTimeString()
        ];
    }
    public function includecategory(SubCategory $subCategory)
    {
        if (!$subCategory->category) {
            return null;
        }
        return $this->item($subCategory->category, App::make(App\Http\Transformers\CategoryTranformer::class));
    }
}
