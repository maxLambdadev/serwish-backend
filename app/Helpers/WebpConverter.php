<?php

namespace App\Helpers;

class WebpConverter
{

    public static int $quality = 100;


    public static function convertFromAny(string $path): bool{
        $exploded = explode('.', $path);

        if (count($exploded) == 0) {
            return false;
        }

        $ext = $exploded[ count($exploded) -1 ];

        if ($ext == 'jpg' || $ext == 'jpeg'){
            return self::convertFromJPG($path);
        }else if ($ext == 'png'){
           return  self::convertFromPNG($path);
        }

        return false;
    }

    public static function convertFromJPG(string $path){
        $image = storage_path($path);

        $im = imagecreatefromjpeg($image);

        $newImagePath = str_replace("jpg", "webp", $image);
        $newImagePath = str_replace("jpeg", "webp", $newImagePath);

        $saved = imagewebp($im, $newImagePath, self::$quality);

        if ($saved) imagedestroy($im);

        return $saved;
    }

    public static function convertFromPNG(string $path){
        return false;
        //todo png not working
        $image = storage_path($path);

        $im = imagecreatefrompng($image);

        imagepalettetotruecolor($im);
        imageAlphaBlending($im, true);
        imageSaveAlpha($im, true);

        $newImagePath = str_replace("png", "webp", $image);

        $saved = imagewebp($im, $newImagePath, self::$quality);

        if ($saved) imagedestroy($im);

        return $saved;
    }
}
