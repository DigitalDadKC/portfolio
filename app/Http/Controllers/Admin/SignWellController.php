<?php

namespace App\Http\Controllers\Admin;

use App\Mail\LogMail;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SignWellController extends Controller
{
    public function handle(Request $request)
    {
        Log::info($request);
        $event = $request->event['type'];
        $name = $request->data['object']['recipients'][0]['name'];
        $contract = Contract::where('signwell_id', $request->data['object']['id'])->first();
        Log::info($contract);

        match ($event) {
            'document_created' => $this->handleCreated($contract),
            'document_sent' => $this->handleSent($contract),
            'document_viewed' => $this->handleViewed($contract),
            'document_in_progress' => $this->handleInProgress($contract),
            'document_signed' => $this->handleSigned($contract),
            'document_completed' => $this->handleCompleted($contract),
            'document_expired' => $this->handleExpired($contract),
            'document_canceled' => $this->handleCanceled($contract),
            'document_declined' => $this->handleDeclined($contract),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    protected function handleCreated(Contract $contract) {
        $action = 'Created';
        $contract->update([
            'status' => 'created'
        ]);
        // Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleSent(Contract $contract)
    {
        $action = 'Sent';
        $contract->update([
            'status' => 'sent'
        ]);
        // Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleViewed(Contract $contract)
    {
        $action = 'Viewed';
        $contract->update([
            'status' => 'viewed'
        ]);
        // Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleCompleted(Contract $contract)
    {
        $action = 'Completed';
        $contract->update([
            'status' => 'completed'
        ]);
        // Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleCanceled(Contract $contract) {
        $action = 'Canceled';
        $contract->update([
            'status' => 'canceled'
        ]);
        // Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }
}
