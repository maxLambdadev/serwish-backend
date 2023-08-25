<?php

namespace App\Helpers;

use App\Models\Blog\CategoryBack;
use App\Models\ServicePhoneClickStatistics;
use App\Models\ServiceStatistics;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticHelper
{

    public static function updateServiceStatistic(int $serviceId, string $ip): void{

        if (!self::statisticExists($serviceId, $ip)){
            self::updateStatisticRecord($serviceId, $ip);
        }

    }

    public static function updateServiceClickStatistic(int $serviceId, string $ip): void{

        if (!self::clickStatisticExists($serviceId, $ip)){
            self::updateClickStatisticRecord($serviceId, $ip);
        }
    }

    public static function updateCategoryViewStatistic(int $catId, string $ip){

        if (!self::categoryStatisticExists($catId,$ip)){
            self::updateCategoryStatisticRecord($catId,$ip);
        }

    }

    //todo override DB::facade no need dry

    private static function categoryStatisticExists(int $catId, string $ip) : bool{
        return CategoryBack::where('category_id','=',$catId)
            ->where('ip_address','=', $ip)
            ->where('created_at', '>', Carbon::now()->subHours(24)->toDateTimeString() )
            ->first() != null;
    }

    private static function updateCategoryStatisticRecord(int $serviceId, string $ip): void{
        CategoryBack::create([
            'view_incr'     => 1,
            'ip_address'    => $ip,
            'category_id'   => $serviceId,
        ]);
    }


    private static function updateStatisticRecord(int $serviceId, string $ip): void{
        ServiceStatistics::create([
            'ip_address'    => $ip,
            'service_id'    => $serviceId,
            'count'         => 1
        ]);
    }


    private static function statisticExists(int $serviceId, string $ip): bool{
        return ServiceStatistics::where('service_id','=',$serviceId)
                ->where('ip_address','=', $ip)
                ->where('created_at', '>', Carbon::now()->subHours(24)->toDateTimeString() )
                ->first() != null;
    }


    private static function updateClickStatisticRecord(int $serviceId, string $ip): void{
        ServicePhoneClickStatistics::create([
            'ip_address'    => $ip,
            'service_id'    => $serviceId,
            'count'         => 1
        ]);
    }


    private static function clickStatisticExists(int $serviceId, string $ip): bool{
        return ServicePhoneClickStatistics::where('service_id','=',$serviceId)
                ->where('ip_address','=', $ip)
                ->where('created_at', '>', Carbon::now()->subMinutes(30)->toDateTimeString() )
                ->first() != null;
    }
}
