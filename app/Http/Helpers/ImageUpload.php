<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2/23/18
 * Time: 8:07 PM
 */

namespace App\Http\Helpers;

use Intervention\Image\Facades\Image;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
class ImageUpload
{
    public static function saveImage($image, $width, $height, $filename, $extension, $folder){
        $imageSave = Image::make($image)->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->encode($extension);
        Storage::disk('public')->put($folder . '/' . $filename . '.' . $extension, $imageSave->__toString());
        return Storage::disk('public')->url($folder . '/' . $filename . '.' . $extension);
    }
}