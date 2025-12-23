<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SignWellController extends Controller
{
   public function __invoke(Request $request)
    {
        dd($request);
        // Log full payload for debugging
        Log::info('SignWell Webhook Received', $request->all());

        $event = $request->input('event');

        match ($event) {
            'document.completed' => $this->handleCompleted($request),
            'document.signed'    => $this->handleSigned($request),
            default              => Log::info('Unhandled SignWell event', compact('event')),
        };

        return response()->json(['status' => 'ok']);
    }

    protected function handleSigned(Request $request)
    {
        $document = $request->input('data.document');

        // Example: update your local document record
        // Document::where('signwell_id', $document['id'])
        //     ->update(['status' => 'signed']);

        Log::info('Document signed', [
            'document_id' => $document['id'] ?? null,
        ]);
    }

    protected function handleCompleted(Request $request)
    {
        $document = $request->input('data.document');

        Log::info('Document completed', [
            'document_id' => $document['id'] ?? null,
        ]);
    }
}
