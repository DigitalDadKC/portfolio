<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Client;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use App\Models\ClientInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\ClientInvoiceItem;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/invoices/Index', [
            'invoices' => ClientInvoice::with('client', 'client_invoice_items')->latest()->get(),
            'clients' => Client::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'date_created' => 'required',
            'due_date' => 'required',
            'terms_and_conditions' => 'required',
            'client_id' => 'required',
            'line_items.*.description' => 'required',
            'line_items.*.price' => 'required|numeric',
            'line_items.*.quantity' => 'required|integer',
        ]);

        $invoice = ClientInvoice::create([
            'number' => $request->number,
            'date_created' => $request->date_created,
            'due_date' => $request->due_date,
            'terms_and_conditions' => $request->terms_and_conditions,
            'paid' => 'unpaid',
            'client_id' => $request->client_id,
        ]);

        foreach ($request->line_items as $item) {
            ClientInvoiceItem::create([
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'client_invoice_id' => $invoice['id'],
                ]
            );
        }

        return back()->with('success', 'Invoice created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientInvoice $invoice)
    {
        $request->validate([
            'number' => 'required',
            'date_created' => 'required',
            'due_date' => 'required',
            'terms_and_conditions' => 'required',
            'client_id' => 'required',
        ]);

        $invoice->update([
            'number' => $request->number,
            'date_created' => $request->date_created,
            'due_date' => $request->due_date,
            'terms_and_conditions' => $request->terms_and_conditions,
            'paid' => 'unpaid',
            'client_id' => $request->client_id,
        ]);

        $idsToKeep = collect($request->client_invoice_items)->pluck('id')->toArray();
        ClientInvoiceItem::where('client_invoice_id', $invoice->id)->whereNotIn('id', $idsToKeep)->delete();

        foreach ($request->line_items as $item) {
            ClientInvoiceItem::updateOrCreate([
                'id' => $item['id'] ?? null,
            ], [
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'client_invoice_id' => $invoice['id'],
                ]
            );
        }

        return back()->with('success', 'Invoice created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientInvoice $invoice)
    {
        $invoice->delete();

        return back()->with('success', 'Invoice deleted successfully.');
    }

    public function sendInvoice(ClientInvoice $clientInvoice)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.key'));
        $clientInvoice->load('client', 'client_invoice_items');
        $lineItems = [];
        $totalPrice = 0;

        foreach($clientInvoice->client_invoice_items as $item) {
            $totalPrice += $item->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->description,
                    ],
                    'unit_amount' => $item->price*100
                ],
                'quantity' => $item->quantity,
            ];
        }

        $invoice = $clientInvoice;
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        $clientInvoice->update([
            'session_id' => $checkout_session->id,
            'total_price' => $totalPrice,
        ]);

        $pdf = Pdf::loadView('pdf.invoice-pdf', compact('clientInvoice', 'checkout_session'))->output();

        Mail::to($clientInvoice->client->email)->send(new InvoiceMail($clientInvoice->toArray(), $pdf, $checkout_session));
        return back()->with('success', 'Invoice sent successfully.');
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.key'));

        $sessionId = $request->get('session_id');
        $client = null;

        try{
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if(!$session) {
                throw new NotFoundHttpException;
            }
            $client = $session->customer_details;
            $invoice = ClientInvoice::where('session_id', $session->id)->first();
            if(!$invoice) {
                throw new NotFoundHttpException;
            }
            if($invoice->paid === 'unpaid') {
                $invoice->paid = 'paid';
                $invoice->save();
            }

            return Inertia::render('Success', [
                // 'customer' => $client,
                // 'session' => $session,
                // 'order' => $invoice,
            ]);
        } catch(\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function webhook() {

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $invoice = ClientInvoice::where('session_id', $session->id)->first();
                if ($invoice && $invoice->paid === 'unpaid') {
                    $invoice->paid = 'paid';
                    $invoice->save();
                    // Send email to customer
                }

            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}
