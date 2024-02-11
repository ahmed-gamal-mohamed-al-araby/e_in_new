<?php

use App\Models\PersonClient;
use Illuminate\Database\Seeder;

class PersonClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PersonClient::create([
            'name' => 'Person Client1',
            'national_id' => '20101010112345',
            'mobile' => '01111111111',
            'address_id' => 3,
            'approved' => 1,
        ]);

        PersonClient::create([
            'name' => 'Person Client2',
            'national_id' => '20101010112346',
            'mobile' => '01111111111',
            'address_id' => 2,
            'approved' => 1,
        ]);
    }
}
