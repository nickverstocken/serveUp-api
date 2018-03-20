<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 3/19/18
 * Time: 11:22 PM
 */

namespace App\Http\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;
use Illuminate\Support\Facades\Storage;
class CategoryTranformer extends TransformerAbstract
{
    public function transform(Category $category)
    {

        return [
            'id' => $category->id,
            'name' => trim($category->name),
            'description' => trim($category->description),
            'picture' => Storage::disk('public')->url($category->picturePath)
        ];
    }
}