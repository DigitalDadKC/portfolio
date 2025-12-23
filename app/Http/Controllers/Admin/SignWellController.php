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
        $event = $request->input('event')['type'];
        Log::info($event);
        Log::info(gettype($event['type']));

        match ($event['type']) {
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
        Mail::to(config('mail.from.address'))->send(new LogMail($request));
    }

    protected function handleSent(Request $request)
    {
        Log::info('sent!');
        Mail::to(config('mail.from.address'))->send(new LogMail($request));
    }

    protected function handleViewed(Request $request)
    {
        Log::info('viewed!');
        Mail::to(config('mail.from.address'))->send(new LogMail($request->all()));
    }

    protected function handleCompleted(Request $request)
    {
        Log::info('completed!');
        Mail::to(config('mail.from.address'))->send(new LogMail($request->all()));
    }

    protected function handleCanceled(Request $request) {
        Log::info('cancelled!');
        Mail::to(config('mail.from.address'))->send(new LogMail($request->all()));
    }
}
