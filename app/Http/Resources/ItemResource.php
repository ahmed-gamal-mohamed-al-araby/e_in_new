<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ItemResource extends JsonResource
{
    // private $itemsCount;

    public function __construct($resource, $itemsCount)
    {
        parent::__construct($resource);
        $this->itemsCount = $itemsCount;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //  Global Variable for unit value

        $amountSold = 0.0;
        $currencyExchangeRate = 0.0;
        $discountRate = 0.0;
        $discountAmount = 0.0;
        $salesTotal = 0.0;
        $totalTaxAmount = 0.0;
        $netTotal = 0.0;
        $total = 0.0;
        // Check for currency sold
        if ($this->basicItemData->currency == "EGP") {
            $amountEGP = floatval(number_format(($this->item_price), 5, '.', ''));
        }elseif ($this->basicItemData->currency != "EGP") {
            $amountSold = $this->item_price ;
            $currencyExchangeRate = $this->rate;
            $amountEGP = floatval(number_format(($amountSold * $currencyExchangeRate), 5, '.', ''));
        }

        // check if diciunt rate nullabel
        if ($this->basicItemData->discount_item_rate != null ){
            $discountRate = $this->basicItemData->discount_item_rate;
        }elseif ($this->discount_item_amount != null) {
            $discountAmount = $this->discount_item_amount;
        }

        $poTaxes = $this->basicItemData->purchaseOrderTaxes; // PO Taxes

        $invoice_tax = [];

        $tax = collect([
            "taxType" => 0,
            "subType" => 0,
            "rate" => 0,
            "amount" => 0,
        ]);

        foreach ($poTaxes as  $key=> $POTax) {
            $tax->put("taxType", "T".$POTax->tax_type);
            $tax->put("subType", $POTax->subtype);
            $tax->put("rate", $POTax->tax_rate);
            $tax->put("amount", floatval(number_format(($this->DocumentTaxes[$key]->amount_tax), 5, '.', '')));
            $totalTaxAmount += floatval(number_format(($this->DocumentTaxes[$key]->amount_tax), 5, '.', ''));
            // if(count($this->DocumentTaxes)){
            //     dump('ok' . $key);
            // } else {
            //     dump($POTax);
            //     dump($this->DocumentTaxes);
            //     dump($this);
            // }
            array_push($invoice_tax, clone $tax);
        }

        $salesTotal = floatval(number_format(($amountEGP * $this->quantity), 5, '.', ''));

        // foreach ($invoice_tax as $value) {
        //     // dump($value['amount']);
        //     $totalTaxAmount += $value['amount'] + $totalTaxAmount;
        // }
        // dump($totalTaxAmount);
        // dump($totalTaxAmount);
        $total = floatval(number_format(( $salesTotal+ $totalTaxAmount )- ($discountAmount + $this->basicItemData->items_discount) , 5, '.', ''));
        // dump($total);
        // $poTaxes = $this->documentItems->basicItemData->purchaseOrderTaxes; // PO Taxes
        // Str::limit($this->description, 300, '...');
        $description =  strlen($this->description) > 40 ? Str::limit($this->description, 40) : $this->description;
        // dump($poTaxes);
        // Str::limit($this->description, 200);

        return [
            'description' =>  $this->itemsCount > 20 ? $description : $this->description,
            'itemType' => $this->basicItemData->product->standard_code_type,
            'itemCode' => $this->basicItemData->product->product_code,
            'internalCode' => $this->basicItemData->product->internal_code,
            'unitType' => $this->basicItemData->unit,
            'quantity' => $this->quantity,
            'unitValue' => [
                'currencySold'=> $this->basicItemData->currency,
                'amountSold' => $amountSold,
                'currencyExchangeRate' => $currencyExchangeRate,
                'amountEGP' => floatval(number_format($amountEGP, 5, '.', '')),
            ],
            'salesTotal'=> floatval(number_format(($amountEGP * $this->quantity), 5, '.', '')) ,
            'valueDifference' => $this->value_difference ?? 0,
            'totalTaxableFees' => $this->taxable_fees ?? 0,
            'discount' => [
                'rate' => $discountRate,
                'amount' => floatval(number_format($discountAmount, 5, '.', '')),
            ],
            'netTotal' => floatval(number_format(($amountEGP * $this->quantity) - $discountAmount, 5, '.', '')),
            'itemsDiscount' => $this->basicItemData->items_discount ?? 0,
            'taxableItems' => $invoice_tax,
            'total' => $total,//$this->total_amount,
        ];

    }
}
