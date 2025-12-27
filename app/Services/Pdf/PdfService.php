<?php

namespace App\Services\Pdf;

use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Services\SignWell\SignWellService;

class PdfService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected SignWellService $signWell) {

    }
    public function createContract(Contract $contract)
    {
        $pdf = Pdf::loadView('pdf.contract', compact('contract'));
        $content = $pdf->output();

        $file_path = 'contract-' . $contract->id. '-' . uniqid() . '.pdf';
        $contract->update([
            'file_path' => $file_path,
            'status' => 'created',
        ]);

        Storage::disk('local')->put('contracts/' . $contract->file_path, $content);
    }

    public function sendContract(Contract $contract,) {
        $document = Storage::disk('local')->get('contracts/' . $contract->file_path);

        $response = $this->signWell->create([
                'test_mode' => true,
                'name' => 'Web App Contract',
                'subject' => 'Web App Contact from DigitalDad, LLC',
                'embedded_signing' => true,
                'embedded_signing_notifications' => false,
                'recipients' => [
                    [
                        'id' => 1,
                        'name' => $contract->employee['name'],
                        'email' => $contract->employee['email'],
                        'passcode' => 'WEBDEVCONTRACT',
                        'subject' => 'Web App Contract from DigitalDad',
                        'message' => 'Please review and sign this contract for your new web app. Passcode is \'WEBDEVCONTRACT\'',
                        'send_email' => true,
                    ]
                ],
                'fields' => [
                                [
                                    [
                                        'type' => 'signature',
                                        'required' => true,
                                        'fixed_width' => false,
                                        'x' => 90,
                                        'y' => 600,
                                        'page' => 2,
                                        'recipient_id' => 1
                                    ],
                                    [
                                        'type' => 'date',
                                        // 'required' => true,
                                        'fixed_width' => false,
                                        'lock_sign_date' => false,
                                        'x' => 90,
                                        'y' => 650,
                                        'page' => 2,
                                        'recipient_id' => 1
                                    ]
                                ]
                            ],
                'files' => [
                    [
                        'name' => $contract->file_path,
                        'file_base64' => base64_encode($document),
                    ]
                ],
        ]);

        $contract->update([
            // 'status' => 'sent',
            'signwell_id' => $response['id'],
        ]);
    }

    public function destroyContract($contract) {
        Storage::disk('local')->delete('contracts/' . $contract->file_path);

        if($contract->signwell_id) {
            $this->signWell->delete($contract->signwell_id);
            $contract->update([
                // 'status' => 'canceled',
                'signwell_id' => NULL,
            ]);
        }
    }
}
