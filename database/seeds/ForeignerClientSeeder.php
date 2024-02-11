<?php

use App\Models\ForeignerClient;
use Illuminate\Database\Seeder;

class ForeignerClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ForeignerClient::create([
            'company_name' => 'foreigner Client1',
            'person_name' => 'ForeignerClient User',
            'person_mobile' => '01111111111',
            'person_email' => 'business_clientUser1@email.com',
            'address_id' => 1,
            'vat_id' => 123,
            'approved' => 1,
        ]);

        ForeignerClient::create([
            'company_name' => 'foreigner Client2',
            'person_name' => 'ForeignerClient2 User',
            'person_mobile' => '02222222222',
            'person_email' => 'business_clientUser2@email.com',
            'address_id' => 2,
            'vat_id' => 456,
            'approved' => 1,
        ]);
    }
}
