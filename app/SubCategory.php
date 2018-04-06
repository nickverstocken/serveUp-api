<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Category $category
 *
 * @package App
 */

class SubCategory extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(\App\Category::class);
    }
}
