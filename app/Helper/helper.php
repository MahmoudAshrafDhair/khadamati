<?php

use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

function get_local_lang()
{
    return app()->getLocale();
}

function get_local_dir()
{
    return app()->getLocale() == 'ar' ? 'rtl' : 'ltr';
}

function change_trans ()
{
    return app()->getLocale() == 'ar' ? 'rtl.' : '';
}
function getLocalTrans()
{
    return LaravelLocalization::getCurrentLocale() === 'ar';
}

function uploadImage($image, $directory)
{
    $image_name = time() + rand(1, 1000000000) . '.' . $image->getClientOriginalExtension();
    $path = 'uploads/images/' . $directory . '/' . $image_name;
    Storage::disk('public')->put($path, file_get_contents($image));
    return $path;
}
