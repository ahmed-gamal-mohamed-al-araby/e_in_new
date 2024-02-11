<?php

use App\Models\Address;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $address = Address::create([
            'country_id' => 1,
            'city_id'  => 1,
            'region_city' => 'Sheraton Al Matar, El Nozha',
            'street' => 'Markez Al Malomat',
            'building_no'  => '19',
        ]);

        Company::create([
            'company_name'=>'المشروعات الهندسية للإنشاءات المدنية و المعدنية',
            'commercial_registeration_number'=>'37599',
            'tax_id_number'=>'311-045-022',
            'tax_file_number' => '555-5-00135-410-00-00',
            'address_id' => $address->id,
        ]);
    }
}
