<?php

namespace App\Helpers;

use App\Models\Services;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceHelper
{

    public static function getOrderedServices($list, $perPage = 16){

        $vipAndSerwishQuality = $list->whereNotNull('packet_id')
            ->where('has_serwish_quality','=', true)
            ->sortBy('sorted_at');
        if ($vipAndSerwishQuality->first() !== null){
            $vip = $vipAndSerwishQuality->first();
            $vip->sorted_at = Carbon::now();
            $vip->save();
        }


        $onlySerwishQuality = $list
            ->where('packet_id', '=', null)
            ->where('has_serwish_quality', '=', true)
            ->sortBy('sorted_at');


        if ($onlySerwishQuality->first() !== null){
            $vip = $onlySerwishQuality->first();
            $vip->sorted_at = Carbon::now();
            $vip->save();
        }

        $defaultWithVip = $list->where('has_serwish_quality', '=', false)
            ->whereNotNull('packet_id')
            ->sortBy('sorted_at');

        if ($defaultWithVip->first() !== null){
            $vip = $defaultWithVip->first();
            $vip->sorted_at = Carbon::now();
            $vip->save();
        }

//
        $default = $list->where('has_serwish_quality', '=', false)
            ->whereNull('packet_id')
            ->sortBy('sorted_at');
        if ($default->first() !== null){
            $vip = $default->first();
            $vip->sorted_at = Carbon::now();
            $vip->save();
        }

        $merged = $vipAndSerwishQuality->merge($onlySerwishQuality);
        $merged = $merged->merge($defaultWithVip);
        $merged = $merged->merge($default);



//        echo  '$vipAndSerwishQuality - ' . $vipAndSerwishQuality->count()  . '</br>';
//        echo  '$onlySerwishQuality - ' . $onlySerwishQuality->count() . '</br>';
//        echo  '$defaultWithVip - ' . $defaultWithVip->count() . '</br>';
//        echo  '$default - ' . $default->count() . '</br>';

        return new LengthAwarePaginator($merged, $list->total(), 16, $list->currentPage() );
    }

}
