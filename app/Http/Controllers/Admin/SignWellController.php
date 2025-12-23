<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SignWellController extends Controller
{
    public function __invoke(Request $request) {
        \Log::info('SIGNWELL WEBHOOK HIT', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        return response()->json(['ok' => true]);
    }
    public function handle(Request $request)
    {
        Log::info('SignWell Webhook Received', $request->all());
        $event = $request->input('event');
        // Log everything for debugging

        $event = $request->input('event');

        match ($event) {
            'document.completed' => $this->handleCompleted($request),
            'document.viewed' => $this->handleViewed($request),
            'document.sent' => $this->handleSent($request),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    protected function handleCompleted(Request $request)
    {
        $documentId = $request->input('data.document.id');

        // Update database, notify user, etc.
    }

    protected function handleViewed(Request $request)
    {
        //
    }

    protected function handleSent(Request $request)
    {
        //
    }
}
