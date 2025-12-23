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
        Log::info($event);

        match ($event) {
            'document_created' => $this->handleCreated($request),
            'document_sent' => $this->handleSent($request),
            'document_viewed' => $this->handleViewed($request),
            'document_completed' => $this->handleCompleted($request),
            'document_canceled' => $this->handleCanceled($request),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    protected function handleCreated(Request $request) {
        Log::info('created!');
        Log::info($request->event);
        $subject = "Contract Created for $request->data['object']['recipients'][0]['name']";
        Log::info($subject);
        Mail::to(config('mail.from.address'))->send(new LogMail($subject));
    }

    protected function handleSent(Request $request)
    {
        Log::info('sent!');
        $subject = "Contract Sent for $request->data['object']['recipients'][0]['name']";
        Log::info($subject);
        Mail::to(config('mail.from.address'))->send(new LogMail($subject));
    }

    protected function handleViewed(Request $request)
    {
        Log::info('viewed!');
        $subject = `Contract Viewed for $request->input('data')['object']['recipients'][0]`;
        Log::info($subject);
        Mail::to(config('mail.from.address'))->send(new LogMail($subject));
    }

    protected function handleCompleted(Request $request)
    {
        Log::info('completed!');
        $subject = `Contract Completed for $request->input('data')['object']['recipients'][0]`;
        Log::info($subject);
        Mail::to(config('mail.from.address'))->send(new LogMail($subject));
    }

    protected function handleCanceled(Request $request) {
        Log::info('cancelled!');
        $subject = `Contract Cancelled for $request->input('data')['object']['recipients'][0]`;
        Log::info($subject);
        Mail::to(config('mail.from.address'))->send(new LogMail($subject));
    }
}
