<?php

use App\Models\DeductionType;
use Illuminate\Database\Seeder;

class DeductionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeductionType::create([
            'name' => 'عادى',
            'code' => '1'
        ]);

        DeductionType::create([
            'name' => 'امانات',
            'code' => '2'
        ]);

        DeductionType::create([
            'name' => 'مواد',
            'code' => '3'
        ]);
    }
}
