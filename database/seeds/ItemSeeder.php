<?php

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Item::create([
        //     'purchase_order_id' => 1,
        //     'product_id' => 1,
        //     'description' => 'PO1 product1 desc',
        //     'quantity' => 20,
        //     'unit' => 'M2',
        //     'currency' => "EGP",
        //     'item_price' => 20,
        //     'discount_item_rate' => 25,
        //     'discount_item_amount' => 100,
        //     'taxable_fees' => 60,
        //     'value_difference' => 10,
        //     'items_discount' => 10,
        //     'net_total' => 300,
        //     'total_amount' => 366,
        // ]);

        // Item::create([
        //     'purchase_order_id' => 1,
        //     'product_id' => 2,
        //     'description' => 'PO1 product2 desc',
        //     'quantity' => 60,
        //     'unit' => 'ML',
        //     'currency' => "Euro",
        //     'item_price' => 40,
        //     'discount_item_rate' => 20,
        //     'discount_item_amount' => 240,
        //     'taxable_fees' => 96,
        //     'value_difference' => 25,
        //     'items_discount' => 13,
        //     'net_total' => 960,
        //     'total_amount' => 1320.15,
        // ]);

        // Item::create([
        //     'purchase_order_id' => 2,
        //     'product_id' => 2,
        //     'description' => 'PO2 product2 desc',
        //     'quantity' => 30,
        //     'unit' => 'ML',
        //     'currency' => "USD",
        //     'item_price' => 10,
        //     'discount_item_rate' => 0,
        //     'discount_item_amount' => 10,
        //     'taxable_fees' => 9.45,
        //     'value_difference' => 50,
        //     'items_discount' => 5,
        //     'net_total' => 90,
        //     'total_amount' => 114.795,
        // ]);

        // Item::create([
        //     'purchase_order_id' => 3,
        //     'product_id' => 2,
        //     'description' => 'PO3 product1 desc',
        //     'quantity' => 25,
        //     'unit' => 'Lum',
        //     'currency' => "USD",
        //     'item_price' => 100000,
        //     'discount_item_rate' => 0,
        //     'discount_item_amount' => 0,
        //     'taxable_fees' => 0,
        //     'value_difference' => 0,
        //     'items_discount' => 0,
        //     'net_total' => 900,
        //     'total_amount' => 900,
        // ]);
    }
}
