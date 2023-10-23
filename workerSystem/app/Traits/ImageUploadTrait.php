<?php

namespace App\Traits;

use Illuminate\support\Str;
use Intervention\Image\Facades\Image;

trait ImageUploadTrait
{
    public function uploadImage($image, $directory, $quality, $width = false, $height = false): string
    {
        // 1-making a name to image

        $file_extention = $image->getClientOriginalExtension();
        $file_name = Str::random(20).'.'.$file_extention;

        // 2- creating the directory that images will be saved in
        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        // 3- we tell package what is image
        $img = Image::make($image->getRealPath());
        if ($width == true or $height == true) {
            $img->resize($width, $height);
        }
        $img->save($directory.'/'.$file_name, $quality);

        return $directory.'/'.$file_name;
    }
}
