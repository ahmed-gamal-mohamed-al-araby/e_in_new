<?php

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::create([
            'bank_code' => 1,
            'bank_name' => 'QNB-بنك قطر الوطني الأهلي',
            'currency' => 'EGP',
            'bank_account_number' => '20315158062-95',
            'bank_account_iban' => 'EG540037070808182031515806295',
            'swift_code' => 'QNBAEGCXXXX',
            'bank_address' => '143 El Hegaz St. Heliopolis Square, Heliopolis, Cairo',
            "approved" => 1,
        ]);

        Bank::create([
            'bank_code' => 2,
            'bank_name' => 'QNB-بنك قطر الوطني الأهلي',
            'currency' => 'USD',
            'bank_account_number' => '24635376722-85',
            'bank_account_iban' => 'EG540037070808182463537672285',
            'swift_code' => 'QNBAEGCXXXX',
            'bank_address' => '143 El Hegaz St. Heliopolis Square, Heliopolis, Cairo',
            "approved" => 1,
        ]);

        Bank::create([
            'bank_code' => 3,
            'bank_name' => 'AHLI-البنك الأهلي المصري',
            'currency' => 'EGP',
            'bank_account_number' => '1383070583499800012',
            'bank_account_iban' => 'EG570003013830705834998000120',
            'swift_code' => 'NBEGEGCXXXX',
            'bank_address' => '3 Hassan Aflatoun St. Ard El Golf , Heliopolis, Cairo',
            "approved" => 1,
        ]);

        Bank::create([
            'bank_code' => 4,
            'bank_name' => 'ALEX-بنك الإسكندرية',
            'currency' => 'EGP',
            'bank_account_number' => '103010226014',
            'bank_account_iban' => 'EG1300051003000001030226014',
            'swift_code' => 'ALEXEGCXXXX',
            'bank_address' => '23 gawad Hossny St. From abd El Khalek Sarwat - Cairo Egypt',
            "approved" => 1,
        ]);

        Bank::create([
            'bank_code' => 5,
            'bank_name' => 'ALEX-بنك الإسكندرية',
            'currency' => 'USD',
            'bank_account_number' => '103010226006',
            'bank_account_iban' => 'EG1300051003000001030226006',
            'swift_code' => 'ALEXEGCXXXX',
            'bank_address' => '23 gawad Hossny St. From abd El Khalek Sarwat - Cairo Egypt',
            "approved" => 1,
        ]);

        Bank::create([
            'bank_code' => 6,
            'bank_name' => 'MISR-بنك مصر',
            'currency' => 'EGP',
            'bank_account_number' => '1910001000005976',
            'bank_account_iban' => 'EG190002019101910001000005976',
            'swift_code' => 'BMISEGCXXXX',
            'bank_address' => 'Banks Complex - Al Rehab City - First Settlement, Cairo-Suez Road - Al Rehab - Cairo',
            "approved" => 1,
        ]);
    }
}
