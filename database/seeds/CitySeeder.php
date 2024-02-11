<?php

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create([
            'name' => 'القاهرة',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'الجيزة',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'القليوبية',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'الشرقية',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'السويس',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'دمياط',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'المنوفية',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'بني سويف',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'الدقهلية',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'الإسكندرية',
            'country_id' => 1,
        ]);

        City::create([
            'name' => 'الفيوم',
            'country_id' => 1,
        ]);
    }
}
