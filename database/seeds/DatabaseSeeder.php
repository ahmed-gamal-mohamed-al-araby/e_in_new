<?php

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
        $this->call(ProductSeeder::class);
        $this->call(BankSeeder::class);

        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);

        $this->call(BusinessClientSeeder::class);
        $this->call(BusinessClientUserSeeder::class);
        $this->call(PersonClientSeeder::class);
        $this->call(ForeignerClientSeeder::class);

        // $this->call(PurchaseOrderSeeder::class);
        // $this->call(ItemSeeder::class);
        // $this->call(PurchaseOrderTaxSeeder::class);
        $this->call(CompanySeeder::class);
        // $this->call(DocumentSeeder::class);
        // $this->call(DocumentItemSeeder::class);
        // $this->call(DocumentTaxesSeeder::class);

        $this->call(LaratrustSeeder::class);
        $this->call(UserTableSeeder::class);

        $this->call(DeductionTypeSeeder::class);
        $this->call(DeductionSeeder::class);
        $this->call(ProductUnitSeeder::class);
        // $this->call(PaymentSeeder::class);
    }
}
