<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetDocumentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uuid, $document;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uuid)
    {
        // $this->document = $document;
        // $this->documentservices = $documentservice;
        $this->uuid = $uuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DocumentService $documentservices)
    {
        $validSteps = [];
        $invalidSteps = [];
        $temp = [];
        $acceptedDocuments = [];

        // Get Status Of Submitted Document
        $document = Document::where('uuid', $this->uuid);
        // dump($document);
        $status = $documentservices->getDocumentDetails($this->uuid);
        // Signature Value
        $signatureValue = $status['response']->signatures[0]->value;
        // dump($status);
        if ($status['status'] == 200) {

            // Check For Invalid Response
            if ($status['response']->status == "Invalid") {
                foreach ($status['response']->validationResults->validationSteps as $key => $step) {
                    if ($step->status == "Invalid") {
                        $temp['Step_Name'] = $step->stepName;
                        $temp['Step_Satus'] = $step->status;
                        $temp['Error_Messsage'] = $step->error->error;
                        $temp['inner_Error'] = $step->error->innerError[0]->error;
                        array_push($invalidSteps, $temp);
                    } else {
                        $temp['Step_Name'] = $step->stepName;
                        $temp['Step_Satus'] = $step->status;
                        array_push($validSteps, $temp);
                    }
                }

                $documentStatus = $status['response']->status;

                $dateTimeReceived =  date('Y-m-d h:i:s', strtotime($status['response']->dateTimeRecevied));

                $publicUrl = $status['response']->publicUrl;

                $document->update([
                    'submit_status'=> false,
                    'uuid' => null,
                    'publicUrl' => $publicUrl,
                    'document_status' => $documentStatus,
                    'date_time_received' => $dateTimeReceived,
                    'invalid_steps' => $invalidSteps,
                    'signature_value' => $signatureValue,
                ]);

            } elseif ($status['response']->status == "Submitted") { // Check for submitted response

                $temp['Submission_Status'] = "Satus: Submitted";
                $temp['Submission_Message'] = "Please Resend This Document Again!, To Update Satus";
                array_push($validSteps, $temp);
                $documentStatus = $status['response']->status;
                $dateTimeReceived =  date('Y-m-d h:i:s', strtotime($status['response']->dateTimeRecevied));
                $publicUrl = $status['response']->publicUrl;

                $document->update([
                    'submit_status' => true,
                    'publicUrl' => $publicUrl,
                    'document_status' => $documentStatus,
                    'date_time_received' => $dateTimeReceived,
                    'invalid_steps' => null,
                    'signature_value' => $signatureValue,
                ]);

                $this->dispatch($this->uuid)->delay(now()->addMinutes(5));

                return redirect()->route('documents.index');

            } else {

                $documentStatus = $status['response']->status;

                $dateTimeReceived =  date('Y-m-d h:i:s', strtotime($status['response']->dateTimeRecevied));
                $publicUrl = $status['response']->publicUrl;

                $document->update([
                    'submit_status' => true,
                    'publicUrl' => $publicUrl,
                    'document_status' => $documentStatus,
                    'date_time_received' => $dateTimeReceived,
                    'invalid_steps' => null,
                    'signature_value' => $signatureValue,
                ]);
            }

        }

        if ($status['status'] == 404) {
            $document->update([
                'submit_status'=> false,
                'uuid' => null,
                'publicUrl' => null,
                'document_status' => null,
                // 'date_time_received' => null,
                'invalid_steps' => null,
                // 'signature_value' => null,
            ]);
        }
    } // end of handle function

    public function failed()
    {
        // Called when the job is failing...
        $document = Document::where('uuid', $this->uuid);
        $document->update([
            'submit_status'=> true,
            // 'uuid' => null,
            'publicUrl' => null,
            'document_status' => 'Sending',
            'date_time_received' => null,
            'invalid_steps' => null,
        ]);
        $this->dispatch($document->uuid)->delay(now()->addMinutes(5));
    }
}
