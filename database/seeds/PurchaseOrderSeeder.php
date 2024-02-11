<?php

use App\Models\PurchaseOrder;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PurchaseOrder::create([
        //     'purchase_order_reference' => 1,
        //     'type' => 'quantity',
        //     'client_id' => 1,
        //     'client_type' => 'b',
        //     'project_name' => 'PO1_project',
        //     'project_number' => 10,
        //     'project_contract_number' => 100,
        //     'payment_terms' => 'PO1_project Payment Terms',
        //     'purchaseorder_document' => 'purchaseorder1_document',
        //     'bank_id' => 1,
        //     'delivery_approach' => 'PO1_project_Approach',
        //     'delivery_packaging' => 'PO1_project_Packaging',
        //     'delivery_validate_date' => '2011-11-11',
        //     'delivery_export_port' => 'PO1_delivery_export_port',
        //     'delivery_country_origin' => 1,
        //     'delivery_gross_weight' => 110,
        //     'delivery_net_weight' => 10,
        //     'delivery_terms' => 'PO1_project_Delivery Terms',
        // ]);

        // PurchaseOrder::create([
        //     'purchase_order_reference' => 2,
        //     'type' => 'quantity',
        //     'client_type' => 'p',
        //     'client_id' => 1,
        //     'project_name' => 'PO2_project',
        //     'project_number' => 20,
        //     'project_contract_number' => 200,
        //     'payment_terms' => 'PO2_project Payment Terms',
        //     'purchaseorder_document' => 'purchaseorder2_document',
        //     'bank_id' => 2,
        //     'delivery_approach' => 'PO2_project_Approach',
        //     'delivery_packaging' => 'PO2_project_Packaging',
        //     'delivery_validate_date' => '2012-12-12',
        //     'delivery_export_port' => 'PO2_delivery_export_port',
        //     'delivery_country_origin' => 2,
        //     'delivery_gross_weight' => 220,
        //     'delivery_net_weight' => 20,
        //     'delivery_terms' => 'PO2_project_Delivery Terms',
        // ]);

        // PurchaseOrder::create([
        //     'purchase_order_reference' => 3,
        //     'type' => 'budget',
        //     'client_type' => 'f',
        //     'client_id' => 1,
        //     'project_name' => 'PO3_project',
        //     'project_number' => 20,
        //     'project_contract_number' => 200,
        //     'payment_terms' => 'PO3_project Payment Terms',
        //     'purchaseorder_document' => 'purchaseorder3_document',
        //     'bank_id' => 2,
        //     'delivery_approach' => 'PO3_project_Approach',
        //     'delivery_packaging' => 'PO3_project_Packaging',
        //     'delivery_validate_date' => '2012-12-12',
        //     'delivery_export_port' => 'PO2_delivery_export_port',
        //     'delivery_country_origin' => 1,
        //     'delivery_gross_weight' => 220,
        //     'delivery_net_weight' => 20,
        //     'delivery_terms' => 'PO3_project_Delivery Terms',
        // ]);
    }
}
