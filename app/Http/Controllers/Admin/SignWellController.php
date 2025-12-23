<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SignWellController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->input('event');

        match($event) {
            'document_created' => $this->handleCreated($request),
            'document_viewed' => $this->handleViewed($request),
            'document_sent' => $this->handleSent($request),
            'document_completed' => $this->handleCompleted($request),
            'document_canceled' => $this->handleCanceled($request),
            default => null,
        }

        return response()->json(['status' => 'ok']);
    }

    protected function handleCreated(Request $request) {
        
        Log::info('Document Created!');
    }

    protected function handleSent(Request $request)
    {
        Log::info('Document Sent!');
    }

    protected function handleViewed(Request $request)
    {
        Log::info('Document Viewed!');
    }

    protected function handleCompleted(Request $request)
    {
        Log::info('Document Completed!');
    }

    protected function handleCanceled(Request $request)
    {
        Log::info('Document Canceled!');
    }
}
