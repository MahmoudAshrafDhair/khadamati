<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Day::query()->create([
           'name' => ['ar' => 'السبت','en' => 'Saturday']
        ]);
        Day::query()->create([
           'name' => ['ar' => 'الاحد','en' => 'Sunday']
        ]);
        Day::query()->create([
           'name' => ['ar' => 'الاثنين','en' => 'Monday']
        ]);
        Day::query()->create([
           'name' => ['ar' => 'الثلاثاء','en' => 'Tuesday']
        ]);
        Day::query()->create([
           'name' => ['ar' => 'الاربعاء','en' => 'Wednesday']
        ]);
        Day::query()->create([
           'name' => ['ar' => 'الخميس','en' => 'Thursday']
        ]);
        Day::query()->create([
           'name' => ['ar' => 'الجمعة','en' => 'Friday']
        ]);
    }
}
