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
//            'logo' => null,
//            'facebook_link' => null,
//            'twitter_link' => null,
//            'instagram_link' => null,
//            'who_are_we' => null,
//            'who_are_we_image' => null,
//            'goal' => null,
//            'goal_image' => null,
//            'vision' => null,
//            'vision_image' => null,
        ]);
    }
}
