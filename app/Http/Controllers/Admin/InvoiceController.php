<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Counter;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InvoiceItem;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Invoices/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Invoices/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function get_all_invoices() {
        $invoices = Invoice::with('client')->orderBy('id', 'desc')->get();

        return response()->json([
            'invoices' => $invoices
        ], 200);
    }

    public function search_invoice(Request $request) {
        $search = $request->get('s');
        if($search != NULL) {
            $invoices = Invoice::with('client')->where('id','LIKE',"%$search%")->get();

            return response()->json([
                'invoices' => $invoices
            ], 200);
        } else {
            return $this->get_all_invoices();
        }
    }

    public function create_invoice(Request $request) {
        $counter = Counter::where('key', 'invoice')->first();
        $random = Counter::where('key', 'invoice')->first();

        $invoice = Invoice::orderBy('id', 'desc')->first();
        if($invoice) {
            $invoice = $invoice->id+1;
            $counters = $counter->value+$invoice;
        } else {
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counters,
            'client_id' => NULL,
            'client' => NULL,
            'date' => date('Y-m-d'),
            'due_date' => NULL,
            'reference' => NULL,
            'discount' => 0,
            'terms_and_conditions' => 'Default Terms & Conditions',
            'items' => [
                [
                    'product_id' => NULL,
                    'product' => NULL,
                    'unit_price' => 0,
                    'quantity' => 1
                ]
            ]
        ];

        return response()->json($formData);
    }

    public function add_invoice(Request $request) {
        dd($request);
        $invoiceitem = $request->input("invoice_item");

        $invoicedata['subtotal'] = $request->input("subtotal");
        $invoicedata['total'] = $request->input("total");
        $invoicedata['client_id'] = $request->input("client_id");
        $invoicedata['number'] = $request->input("number");
        $invoicedata['date'] = $request->input("date");
        $invoicedata['due_date'] = $request->input("due_date");
        $invoicedata['discount'] = $request->input("discount");
        $invoicedata['reference'] = $request->input("reference");
        $invoicedata['terms_and_conditions'] = $request->input("terms_and_conditions");

        $invoice = Invoice::create($invoicedata);

        foreach(json_decode($invoiceitem) as $item) {
            $itemdata['product_id'] = $item->id;
            $itemdata['invoice_id'] = $invoice->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;

            InvoiceItem::create($itemdata);
        }
    }
}
