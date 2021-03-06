<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            DaySeeder::class,
            AdminSeeder::class,
            PermissionTableSeeder::class
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
