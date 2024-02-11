<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\BusinessClient;
use App\Models\ForeignerClient;
use App\Models\PersonClient;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function getClientsFromclientType() // get Foreigner client
    {
        return json_encode(ForeignerClient::where('approved', 1)->pluck('company_name', 'id'));
    }

    public function getDocumentForeignerPurchaseOrder($id)
    {
        $client = ForeignerClient::where('id', $id)->first()->purchaseOrders()->select('id', 'purchase_order_reference')->get();
        return json_encode($client);
    }

    public function getBusinessOrPersonClientData(Request $request)
    {
        if ($request->clientType == 'b') {
            $client = BusinessClient::where('tax_id_number', $request->searchContent)->where('approved', 1)->first();
            $clientCollection = collect();
            $clientCollection->put('id', $client->id);
            $clientCollection->put('name', $client->name);
            $clientCollection->put('address', $client->address->country->name . ' ,' . $client->address->city->name . ', ' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no);
            return json_encode($clientCollection);

            // return json_encode(BusinessClient::where('tax_id_number', $request->searchContent)->select('id', 'name')->first());
        }

        if ($request->clientType == 'p') {
            $client = PersonClient::where('national_id', $request->searchContent)->where('approved', 1)->first();
            $clientCollection = collect();
            $clientCollection->put('id', $client->id);
            $clientCollection->put('name', $client->name);
            $clientCollection->put('address', $client->address->country->name . ' ,' . $client->address->city->name . ', ' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no);
            return json_encode($clientCollection);
        }
    }

    public function getDocumentBusinessOrPersonClientData(Request $request)
    {
        $client = collect();

        // return 147;
        if ($request->clientType == 'b') {
            $_client = BusinessClient::where('tax_id_number', $request->searchContent)->where('approved', 1)->first();
            if ($_client) {
                $clientBasicCollection = collect();
                $clientBasicCollection->put('id', $_client->id);
                $clientBasicCollection->put('name', $_client->name);
                $clientBasicCollection->put('address', $_client->address->country->name . ' ,' . $_client->address->city->name . ', ' . $_client->address->region_city . ', ' . $_client->address->street . ', ' . $_client->address->building_no);

                $client->put('basic', $clientBasicCollection);

                // $client->put('basic', BusinessClient::where('tax_id_number', $request->searchContent)->select('id', 'name', 'address')->first());
                $client->put('purchaseOrders', BusinessClient::where('tax_id_number', $request->searchContent)->where('approved', 1)->first()->purchaseOrders()->where('approved', 1)->select('id', 'purchase_order_reference')->get());
            }
            return json_encode($client);
        }

        if ($request->clientType == 'p') {

            $_client = PersonClient::where('national_id', $request->searchContent)->where('approved', 1)->first();
            if ($_client) {
                $clientBasicCollection = collect();
                $clientBasicCollection->put('id', $_client->id);
                $clientBasicCollection->put('name', $_client->name);
                $clientBasicCollection->put('address', $_client->address->country->name . ' ,' . $_client->address->city->name . ', ' . $_client->address->region_city . ', ' . $_client->address->street . ', ' . $_client->address->building_no);

                $client->put('basic', $clientBasicCollection);

                // $client->put('basic', PersonClient::where('national_id', $request->searchContent)->select('id', 'name', 'address')->first());
                $client->put('purchaseOrders', PersonClient::where('national_id', $request->searchContent)->where('approved', 1)->first()->purchaseOrders()->where('approved', 1)->select('id', 'purchase_order_reference')->get());
            }
            return json_encode($client);
        }
    }

    public function getBusinessOrPersonClientDataByName(Request $request)
    {
        // dump(request()->all());
        if ($request->clientType == 'b') {
            $client = BusinessClient::where('id', $request->searchContent)->first();
            $clientCollection = collect();
            $clientCollection->put('id', $client->id);
            $clientCollection->put('tax_id_number_or_national_id_or_vat_id', $client->tax_id_number);
            $clientCollection->put('address', $client->address->country->name . ' ,' . $client->address->city->name . ', ' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no);
            return json_encode($clientCollection);
        }

        if ($request->clientType == 'p') {
            $client = PersonClient::where('id', $request->searchContent)->first();
            $clientCollection = collect();
            $clientCollection->put('id', $client->id);
            $clientCollection->put('tax_id_number_or_national_id_or_vat_id', $client->national_id);
            $clientCollection->put('address', $client->address->country->name . ' ,' . $client->address->city->name . ', ' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no);
            return json_encode($clientCollection);
        }

        if ($request->clientType == 'f') {
            $client = ForeignerClient::where('id', $request->searchContent)->first();
            $clientCollection = collect();
            $clientCollection->put('id', $client->id);
            $clientCollection->put('tax_id_number_or_national_id_or_vat_id', $client->vat_id);
            if ($client->address->city_id) {
                $clientCollection->put('address', $client->address->country->name . ' ,' . $client->address->city->name . ', ' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no);

            } else {
                $clientCollection->put('address', $client->address->country->name . ' ,' . $client->address->region_city . ', ' . $client->address->street . ', ' . $client->address->building_no);

            }

            return json_encode($clientCollection);
        }
    }

    public function related_document_show()
    {
        return view('pages.client.related_document');
    }

    public function related_document(Request $request)
    {
        $client = null;
        $documents = collect([]);
        $document = [
            'id' => '',
            'document_number' => '',
            'date' => '',
            'type' => '',
            'version' => '',
            'PO_reference' => '',
            'PO_id' => '',
            'submit_status' => '',
            'archive' => '',
        ];
        if ($request->clientType == 'b') {
            $client = BusinessClient::find($request->clientId);
        } else if ($request->clientType == 'p') {
            $client = PersonClient::find($request->clientId);
        } else if ($request->clientType == 'f') {
            $client = ForeignerClient::find($request->clientId);
        }

        if ($client) {
            $purchaseOrders = $client->purchaseOrders;
            foreach ($purchaseOrders as $purchaseOrder) {
                $_documents = $purchaseOrder->documents()->select('id', 'document_number', 'date', 'type', 'version', 'submit_status', 'archive');

                if($request->sent){ // Only sent documents
                    $_documents = $_documents->where('submit_status', 1);
                }

                $_documents = $_documents->get();
                foreach ($_documents as $_document) {
                    $document['id'] = $_document->id;
                    $document['document_number'] = $_document->document_number;
                    $document['date'] = $_document->date;
                    $document['type'] = $_document->type;
                    $document['version'] = $_document->version;
                    $document['PO_reference'] = $purchaseOrder->purchase_order_reference;
                    $document['PO_id'] = $purchaseOrder->id;
                    $document['submit_status'] = $_document->submit_status;
                    $document['archive'] = $_document->archive;
                    $documents->push($document);
                }
            }
        }
        $sorted = $documents->sortBy('id');
        return json_encode($sorted->values()->all());
    }
}
