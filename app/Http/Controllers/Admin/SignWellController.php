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

        match ($event) {
            'document_created' => $this->handleCreated($name),
            'document_sent' => $this->handleSent($name),
            'document_viewed' => $this->handleViewed($name),
            'document_completed' => $this->handleCompleted($name),
            'document_canceled' => $this->handleCanceled($name),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    protected function handleCreated(string $name) {
        Log::info('created!');
        $action = 'Created';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleSent(string $name)
    {
        Log::info('sent!');
        $action = 'Sent';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleViewed(string $name)
    {
        Log::info('viewed!');
        $action = 'Viewed';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleCompleted(string $name)
    {
        Log::info('completed!');
        $action = 'Completed';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleCanceled(string $name) {
        Log::info('cancelled!');
        $action = 'Canceled';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }
}
