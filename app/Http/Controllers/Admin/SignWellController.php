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
    public function __construct(public Contract $contract, public string $name) {}
    public function handle(Request $request)
    {
        $event = $request->event['type'];
        Log::info($request);
        $this->contract = Contract::where('signwell_id', $request->data['object']['id'])->first();
        $this->name = $this->contract->employee['name'];

        match ($event) {
            'document_viewed' => $this->handleViewed(),
            'document_in_progress' => $this->handleInProgress(),
            'document_signed' => $this->handleSigned(),
            'document_completed' => $this->handleCompleted(),
            'document_expired' => $this->handleExpired(),
            'document_declined' => $this->handleDeclined(),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    protected function handleViewed()
    {
        $this->contract->update([
            'status' => 'viewed'
        ]);
        $action = 'Viewed';
        Mail::to(config('mail.from.address'))->send(new LogMail($this->name, $action));
    }

    protected function handleInProgress()
    {
        $this->contract->update([
            'status' => 'completed'
        ]);
        $action = 'In Progress';
        Mail::to(config('mail.from.address'))->send(new LogMail($this->name, $action));
    }

    protected function handleSigned() {
        $this->contract->update([
            'status' => 'canceled'
        ]);
        $action = 'Signed';
        Mail::to(config('mail.from.address'))->send(new LogMail($this->name, $action));
    }

    protected function handleCompleted() {
        $this->contract->update([
            'status' => 'canceled'
        ]);
        $action = 'Completed';
        Mail::to(config('mail.from.address'))->send(new LogMail($this->name, $action));
    }

    protected function handleExpired() {
        $this->contract->update([
            'status' => 'expired'
        ]);
        $action = 'Expired';
        Mail::to(config('mail.from.address'))->send(new LogMail($this->name, $action));
    }

    protected function handleDeclined() {
        $this->contract->update([
            'status' => 'declined'
        ]);
        $action = 'Declined';
        Mail::to(config('mail.from.address'))->send(new LogMail($this->name, $action));
    }
}
