<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderTaxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $docuemntTax = $this->documenttax;

        // dd($docuemntTax);
        // dump(gettype($this->documenttax->amount_tax));

        return [
            'taxType'=> 'T'.$this->tax_type,
            'subType'=> $this->subtype,
            'rate'=> $this->tax_rate,
            // 'amount'=> $this->documenttaxs->amount_tax,
            'amount'=> floatval($docuemntTax->amount_tax),
        ];
    }
}
