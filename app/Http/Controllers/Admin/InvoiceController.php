<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Mail\InvoiceMail;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Models\ClientInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
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
            'invoices' => ClientInvoice::with('client')->get(),
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
            'total_price' => 'required',
            'terms_and_conditions' => 'required',
            'client_id' => 'required',
        ]);

        $invoice = ClientInvoice::create([
            'total_price' => $request->total_price,
            'number' => $request->number,
            'date_created' => $request->date_created,
            'due_date' => $request->due_date,
            'terms_and_conditions' => $request->terms_and_conditions,
            'paid' => 'unpaid',
            'client_id' => $request->client_id,
        ]);

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
            'total_price' => 'required',
            'terms_and_conditions' => 'required',
            'client_id' => 'required',
        ]);

        $invoice->update([
            'total_price' => $request->total_price,
            'number' => $request->number,
            'date_created' => $request->date_created,
            'due_date' => $request->due_date,
            'terms_and_conditions' => $request->terms_and_conditions,
            'paid' => 'unpaid',
            'client_id' => $request->client_id,
        ]);

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
        $clientInvoice->load('client');
        $lineItems = [];

        \Stripe\Stripe::setApiKey(config('services.stripe.key'));
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'name',
                    'images' => null
                ],
                'unit_amount' => 250*100
            ],
            'quantity' => 1,
        ];

        $invoice = $clientInvoice;
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        $clientInvoice->update([
            'session_id' => $checkout_session->id
        ]);

        $company = Company::first();

        $pdf = Pdf::loadView('pdf.invoice-pdf', compact('clientInvoice', 'checkout_session', 'company'))->output();

        Mail::to($clientInvoice->client->email)->send(new InvoiceMail($clientInvoice->toArray(), $pdf, $checkout_session, $company->toArray()));
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
            if($invoice->status === 'unpaid') {
                $invoice->status = 'paid';
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
}
