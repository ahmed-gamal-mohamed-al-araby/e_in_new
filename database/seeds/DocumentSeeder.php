<?php

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Document::create([
        //     'date' => '2020-03-03',
        //     'document_number' => '2020-001',
        //     'company_id' => 1,
        //     'extra_invoice_discount' => 120,
        //     'purchase_order_id' => 1,
        //     'type' => 'I',
        //     'version' => '0.9',
        // ]);

        // Document::create([
        //     'date' => '2021-04-04',
        //     'document_number' => '2021-001',
        //     'company_id' => 1,
        //     'extra_invoice_discount' => 121,
        //     'purchase_order_id' => 2,
        //     'type' => 'I',
        //     'version' => '1.0',
        // ]);

        // Document::create([
        //     'date' => '2021-04-04',
        //     'document_number' => '2022-001',
        //     'company_id' => 1,
        //     'extra_invoice_discount' => 0,
        //     'purchase_order_id' => 3,
        //     'type' => 'I',
        //     'version' => '1.0',
        // ]);
    }
}
