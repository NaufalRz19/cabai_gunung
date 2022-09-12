<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

trait HasImage
{
    public function uploadImage(UploadedFile $request, $path)
    {
        $image = $request;
        $imageName = $image->hashName();
        $image->storeAs('public/'.$path, $imageName);

        return $imageName;
    }
}
