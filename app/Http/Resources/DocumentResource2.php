<?php

namespace App\Http\Resources;

use App\Models\Document;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource2 extends JsonResource
{
    /**
     * Transform the resource into an array.
     */

    public function toArray($request)
    {

        // General declared variables
        $totalSalesAmount = 0.00000;
        $totalDiscountAmount = 0.00000;
        $totalItemsDiscountAmount = 0.00000;
        $totalnetAmount = 0.000000;
        $totalAmount = 0.00000;
        $totalTaxAmount = 0.00000;
        $taxes = [];
        $taxesTotlas = [];
        $temp = [];

        $test = [];
        //  Check for Client Type
        if ($this->purchaseOrder->client_type == "f") {
            $clientType = json_decode(json_encode($this->purchaseOrder->client_type)); //(enumirate type)  convert recevier country code to string
            $clientName = json_decode(json_encode($this->purchaseOrder->foreignerClient->company_name)); // convert recevier name to string
            $recevierId = $this->purchaseOrder->foreignerClient->vat_id;
            $country = $this->purchaseOrder->foreignerClient->address->country->code;
            if ($this->purchaseOrder->foreignerClient->address->city_id) {
                $governate = $this->purchaseOrder->foreignerClient->address->city->name;
            } else {
                $governate = "";
            }

            $regionCity = $this->purchaseOrder->foreignerClient->address->region_city;
            $street = $this->purchaseOrder->foreignerClient->address->street;
            $buildingNumber = $this->purchaseOrder->foreignerClient->address->building_no;
        } elseif ($this->purchaseOrder->client_type == "p") {
            $clientType = json_decode(json_encode($this->purchaseOrder->client_type)); //(enumirate type)  convert recevier country code to string
            $clientName = json_decode(json_encode($this->purchaseOrder->personClient->name)); // convert recevier name to string
            $recevierId = $this->purchaseOrder->personClient->national_id;
            $country = $this->purchaseOrder->personClient->address->country->code;
            $governate = $this->purchaseOrder->personClient->address->city->name;
            $regionCity = $this->purchaseOrder->personClient->address->region_city;
            $street = $this->purchaseOrder->personClient->address->street;
            $buildingNumber = $this->purchaseOrder->personClient->address->building_no;
        } else {
            $clientType = json_decode(json_encode($this->purchaseOrder->client_type)); //(enumirate type)  convert recevier country code to string
            $clientName = json_decode(json_encode($this->purchaseOrder->businessClient->name)); // convert recevier name to string
            $recevierId = $this->purchaseOrder->businessClient->tax_id_number;
            $country = $this->purchaseOrder->businessClient->address->country->code;
            $governate = $this->purchaseOrder->businessClient->address->city->name;
            $regionCity = $this->purchaseOrder->businessClient->address->region_city;
            $street = $this->purchaseOrder->businessClient->address->street;
            $buildingNumber = $this->purchaseOrder->businessClient->address->building_no;
        }

        $documentItems = $this->items; // get this document item
        $itemsCount = count($this->items); // get items count
        // dump($itemsCount);

        //  For loop to make tax 0 from 1 to 20
        for ($key = 0; $key < 20; $key++) {
            $taxes[$key] = 0;
        }

        // for each to sum all tax type in one array
        foreach ($this->items as $doc_item) {
            foreach ($doc_item->DocumentTaxes as $key => $document_item_tax) {
                $taxes[$doc_item->basicItemData->purchaseOrderTaxes[$key]->tax_type] += $document_item_tax->amount_tax;
            }
        }

        // Add taxType & amount to taxes array
        foreach ($taxes as $index => $tax) {
            if ($tax != 0) {
                $temp['taxType'] = "T" . $index;
                $temp['amount'] = floatval(number_format(($taxes[$index]), 5, '.', ''));
                $totalTaxAmount += floatval(number_format(($taxes[$index]), 5, '.', ''));
                // dump($totalTaxAmount);
                array_push($taxesTotlas, $temp);
            }
        }

        $amountSoldTotal = 0.0;
        $amountSold = 0.0;
        $currencyExchangeRate = 0.0;
        $discountRate = 0.0;
        $tax = collect([
            "taxType" => 0,
            "subType" => 0,
            "rate" => 0,
            "amount" => 0,
        ]);
        $amountEGPTotal=0;
        // for each to make summation for total sales & total discount &total net
        foreach ($documentItems as $index => $item) {
            if ($item->basicItemData->currency == "EGP") {
                $amountEGP = floatval(number_format(($item->item_price), 5, '.', ''));  //$item->item_price
            } elseif ($item->basicItemData->currency != "EGP") {
                $amountSold = $item->item_price;
                $currencyExchangeRate = $item->rate;
                $amountEGP = floatval(number_format(($amountSold * $currencyExchangeRate), 5, '.', ''));
            }

            if ($item->basicItemData->discount_item_rate != null) {
                $discountRate = $item->basicItemData->discount_item_rate;
            } elseif ($item->discount_item_amount != null) {
                $discountAmount = $item->discount_item_amount;
            }

            $poTaxes = $item->basicItemData->purchaseOrderTaxes; // PO Taxes

            $invoice_tax = [];

            $amountEGPTotal += $amountEGP;


            foreach ($poTaxes as  $key => $POTax) {
                $tax->put("taxType", "T" . $POTax->tax_type);
                $tax->put("subType", $POTax->subtype);
                $tax->put("rate", $POTax->tax_rate);
                $tax->put("amount", floatval(number_format(($totalTaxAmount), 5, '.', '')));
                // if(count($this->DocumentTaxes)){
                //     dump('ok' . $key);
                // } else {
                //     dump($POTax);
                //     dump($this->DocumentTaxes);
                //     dump($this);
                // }
                array_push($invoice_tax, clone $tax);
            }

            $totalSalesAmount += floatval(number_format(($amountEGP * $item->quantity), 5, '.', ''));
            $totalDiscountAmount += ($item->discount_item_amount);
            $totalItemsDiscountAmount += ($item->basicItemData->items_discount);
            $totalnetAmount += floatval(number_format(($amountEGP * $item->quantity) - $totalDiscountAmount, 5, '.', ''));
            $totalAmount = floatval(number_format((($totalSalesAmount + $totalTaxAmount) - $this->extra_invoice_discount), 5, '.', ''));

            $amountSoldTotal += $amountSold;
        }

        $test = [];
        $x = array([
            'description' =>  " اعمال مقاولات مشروع " . $this->purchaseOrder->purchase_order_reference,
            'itemType' => "EGS",
            'itemCode' => "10007584",
            'internalCode' => "47",
            'unitType' => "C62",
            'quantity' => 1,
            'unitValue' => [
                'currencySold' => $this->items[0]->basicItemData->currency,
                'amountSold' => $amountSoldTotal,
                'currencyExchangeRate' => $currencyExchangeRate,
                'amountEGP' => floatval(number_format($totalSalesAmount, 5, '.', '')),
            ],
            'salesTotal' => floatval(number_format($totalSalesAmount, 5, '.', '')),
            'valueDifference' => $this->value_difference ?? 0,
            'totalTaxableFees' => $this->taxable_fees ?? 0,
            'discount' => [
                'rate' => $discountRate,
                'amount' => floatval(number_format($totalDiscountAmount, 5, '.', '')),
            ],
            'netTotal' => floatval(number_format($totalSalesAmount, 5, '.', '')),
            'itemsDiscount' => $this[0]->items->basicItemData->items_discount ?? 0,
            'taxableItems' => $invoice_tax,
            'total' => $totalAmount, //$this->total_amount,
        ]);
        // Return Document Json Object
        return [
            'issuer' => [
                'address' => [
                    'branchID' => "0",
                    'country' => "EG",
                    'governate' => "CAIRO",
                    'regionCity' => "Sheraton Heliopolis",
                    'street' => "Markez Al Maalomat",
                    'buildingNumber' => "19",
                    'postalCode' => "11361",
                    'floor' => "",
                    'room' => "",
                    'landmark' => "",
                    'additionalInformation' => "",
                ],
                'type' => "B",
                'id' => str_replace("-", "", $this->company->tax_id_number),
                'name' => $this->company->company_name,
            ],
            'receiver' => [
                'address' => [
                    // 'branchID' => "",
                    'country' => $country,
                    'governate' => $governate,
                    'regionCity' => $regionCity,
                    'street' => $street,
                    'buildingNumber' => $buildingNumber,
                    'postalCode' => "",
                    'floor' => "",
                    'room' => "",
                    'landmark' => "",
                    'additionalInformation' => "",
                ],
                'type' => ucfirst($clientType),
                'id' => str_replace("-", "", $recevierId),
                'name' => $clientName,
            ],
            'documentType' => $this->type,
            'documentTypeVersion' => $this->version,
            'dateTimeIssued' => date('Y-m-d\TH:i:s\Z', strtotime($this->date)),
            'taxpayerActivityCode' => "4100",
            'internalID' => $this->document_number,
            'purchaseOrderReference' => "",
            'purchaseOrderDescription' => "",
            'salesOrderReference' => "",
            'salesOrderDescription' => "",
            'proformaInvoiceNumber' => "",
            'payment' => [
                'bankName' => "",
                'bankAddress' => "",
                'bankAccountNo' => "",
                'bankAccountIBAN' => "",
                'swiftCode' => "",
                'terms' => "",
            ],
            'delivery' => [
                'approach' => "",
                'packaging' => "",
                'dateValidity' => "",
                'exportPort' => "",
                'countryOfOrigin' => "",
                'grossWeight' => 0,
                'netWeight' => 0,
                'terms' => "",
            ],
            'invoiceLines' => $x,
            'totalSalesAmount' => floatval(number_format($totalSalesAmount, 5, '.', '')),
            'totalDiscountAmount' => floatval(number_format($totalDiscountAmount, 5, '.', '')),
            'netAmount' => floatval(number_format($totalnetAmount, 5, '.', '')),
            'taxTotals' => $taxesTotlas,
            'extraDiscountAmount' => floatval(number_format($this->extra_invoice_discount, 5, '.', '')),
            'totalItemsDiscountAmount' => floatval(number_format($totalItemsDiscountAmount, 5, '.', '')),
            'totalAmount' => $totalAmount,
        ];
    }
} // End Of Document Resource
