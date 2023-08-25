<?php

namespace Database\Seeders;

use App\Models\Locales;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Locales::create([
            'name'=>'Georgia',
            'original_name' => 'ქართული',
            'iso_code'  => 'ka',
            'is_active' => true,
            'is_default' => true,
        ]);

        Locales::create([
            'name'=>'English',
            'original_name' => 'ინგლისური',
            'iso_code'  => 'en',
            'is_active' => true,
            'is_default' => false,

        ]);

        Locales::create([
            'name'=>'Russian',
            'original_name' => 'русский',
            'iso_code'  => 'ru',
            'is_active' => true,
            'is_default' => false,
        ]);
    }
}
