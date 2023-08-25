<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class DataMutator
{

    public static function mutateServices($list, $loc = null){
        $list = $list->map(function ($item) use ($loc){
            $locale = $loc;
            if ($loc == null){
                $locale = App::getLocale();
            }
            unset($item->translated);
            $item->translated = $item->translations->where('locale','=',$locale)->first();
            $item->translations = null;
            $item->translated->slug = Beautifier::geoToEng($item->title );

            unset($item->translations);
            $item->translated->description = Str::of(strip_tags($item->description))->limit(200);
            return $item;
        });
        return $list;
    }

    public static function mutateCategories($entity, $loc = null)
    {
        $entity = $entity->map(function ($item) use($loc) {
            $locale = $loc;
            if ($loc == null){
                $locale = App::getLocale();
            }
            unset($item->translated);
            $item->translated = $item->translations->where( 'locale',$locale )->first();
            $item->translated->slug = Beautifier::geoToEng($item->title );

            $item->translations = null;
            foreach ($item->childrens as $children){
                unset($children->translated);
                $children->translated = $children->translations->where( 'locale', $locale )->first();
                $children->translated->slug = Beautifier::geoToEng($children->title );

                unset($children->translations);
            }
            unset($item->translations);
            return $item;


        });
        return $entity;
    }

    public static function mutateSpecialists($list)
    {
        $list = $list->map(function ($item){
            $item->serviceCategories = "";
            $img = !$item->images->isEmpty() ? $item->images[0] : null;
            $item->image = $img != null ? asset('storage/'.$img->path) : null;
            unset($item->images);

            if ($item->services != null){
                foreach ($item->services as $service){
                    if (!$service->categories->isEmpty()){
                        foreach ($service->categories as $category){
                            $item->serviceCategories .= $category->title. ", ";
                        }
                    }
                }
            }
            unset($item->services);
            return $item;
        });
        return $list;
    }

    public static function mutateImage($list){
        $list = $list->map(function ($item){
            $img = !$item->images->isEmpty() ? $item->images[0] : null;
            $item->image = $img != null ? asset('storage/'.$img->path) : null;
            unset($item->images);
            return $item;
        });
        return $list;
    }
}
