<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::query()->create([
            'Terms_and_Conditions' => ['ar' => '', 'en' => ''],
            'privacy_policy' => ['ar' => '', 'en' => ''],
        ]);
    }
}
