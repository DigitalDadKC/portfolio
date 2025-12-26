<?php

namespace App\Http\Controllers\Admin;

use App\Mail\LogMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SignWellController extends Controller
{
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
        $action = 'Created';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleSent(string $name)
    {
        $action = 'Sent';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleViewed(string $name)
    {
        $action = 'Viewed';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleCompleted(string $name)
    {
        $action = 'Completed';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }

    protected function handleCanceled(string $name) {
        $action = 'Canceled';
        Mail::to(config('mail.from.address'))->send(new LogMail($name, $action));
    }
}
