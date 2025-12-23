<?php

namespace App\Http\Controllers\Admin;

use App\Mail\LogMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SignWellController extends Controller
{
    // public function __invoke(Request $request) {
    //     \Log::info('SIGNWELL WEBHOOK HIT', [
    //         'headers' => $request->headers->all(),
    //         'payload' => $request->all(),
    //     ]);

    //     return response()->json(['ok' => true]);
    // }
    public function handle(Request $request)
    {
        $event = $request->event['type'];
        $name = $request->data['object']['recipients'][0]['name'];
        $subject = "Contract Action for $name :";

        match ($event) {
            'document_created' => $this->handleCreated($subject),
            'document_sent' => $this->handleSent($subject),
            'document_viewed' => $this->handleViewed($subject),
            'document_completed' => $this->handleCompleted($subject),
            'document_canceled' => $this->handleCanceled($subject),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    protected function handleCreated(string $subject) {
        Log::info('created!');
        $newSubject = $subject . ': Created';
        Log::info($subject);
        Mail::to(config('mail.from.address'))->send(new LogMail($newSubject));
    }

    protected function handleSent(string $subject)
    {
        Log::info('sent!');
        $newSubject = $subject . ': Sent';
        Mail::to(config('mail.from.address'))->send(new LogMail($newSubject));
    }

    protected function handleViewed(string $subject)
    {
        Log::info('viewed!');
        $newSubject = $subject . ': Viewed';
        Mail::to(config('mail.from.address'))->send(new LogMail($newSubject));
    }

    protected function handleCompleted(string $subject)
    {
        Log::info('completed!');
        $newSubject = $subject . ': Completed';
        Mail::to(config('mail.from.address'))->send(new LogMail($newSubject));
    }

    protected function handleCanceled(string $subject) {
        Log::info('cancelled!');
        $newSubject = $subject . ': Canceled';
        Log::info($subject);
        Mail::to(config('mail.from.address'))->send(new LogMail($newSubject));
    }
}
