<?php

namespace App\Http\Controllers\Invoicing;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Material;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\MaterialResource;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];
        $filters['search'] = $request->search ?? '';
        $filters['unpaid'] = json_decode($request->input('unpaid', false));
        $filters['trashed'] = json_decode($request->input('trashed', false));
        $filters['pages'] = $request->pages ? $request->pages : 10;
        $filters['customers'] = $request->input('customers', Customer::get()->map(fn($item) => $item->id)) ?? [];

        $invoices = Invoice::query()->with('customer')
            ->when($filters['search'], fn($query, $search) => $query->where('number', 'like', "%{$search}%")->orWhere('reference', 'like', "%{$search}%"))
            ->when($filters['unpaid'], fn($query) => $query->where('paid', 0))
            ->when($filters['trashed'], fn($query) => $query->onlyTrashed(), fn($query) => $query->whereNull('deleted_at'))
            ->whereIn('customer_id', $filters['customers'])
            ->orderBy('created_at', 'desc');

        $invoices = ($invoices->paginate($filters['pages'])->isEmpty())
            ? InvoiceResource::collection($invoices->with('invoice_items.material.material_unit_size', 'invoice_items.material.material_category')->paginate($filters['pages'], ['*'], 'page', 1)->withQueryString())
            : InvoiceResource::collection($invoices->with('invoice_items.material.material_unit_size', 'invoice_items.material.material_category')->paginate($filters['pages'])->withQueryString());

        return inertia('invoicing/invoicing/Index', [
            'invoices' => fn() => $invoices,
            'customers' => CustomerResource::collection(Customer::with('state')->orderBy('name')->get()),
            'materials' => MaterialResource::collection(Material::orderBy('name')->get()),
            'filters' => fn() => $filters,
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
            'reference' => 'required',
            'terms_and_conditions' => 'required',
            'discount' => 'required|integer',
            'customer_id' => 'required',
            'invoice_items' => 'array',
            'invoice_items.*.id' => 'sometimes|exists:invoice_items,id',
            'invoice_items.*.material_id' => 'required|exists:materials,id',
            'invoice_items.*.quantity' => 'required|integer|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'number' => $request->number,
            'date_created' => $request->date_created,
            'due_date' => $request->due_date,
            'reference' => $request->reference,
            'terms_and_conditions' => $request->terms_and_conditions,
            'discount' => $request->discount,
            'customer_id' => $request->customer_id,
        ]);

        foreach ($request->invoice_items as $item) {
            InvoiceItem::create(
                [
                    'invoice_id' => $invoice->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price']*100,
                ]
            );
        }

        return back()->with('success', 'Invoice updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'number' => 'required',
            'date_created' => 'required',
            'due_date' => 'required',
            'reference' => 'required',
            'terms_and_conditions' => 'required',
            'discount' => 'required|integer',
            'customer_id' => 'required',
            'invoice_items' => 'array',
            'invoice_items.*.id' => 'sometimes|exists:invoice_items,id',
            'invoice_items.*.material_id' => 'required|exists:materials,id',
            'invoice_items.*.quantity' => 'required|integer|min:1',
            'invoice_items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'number' => $request->number,
            'date_created' => $request->date_created,
            'due_date' => $request->due_date,
            'reference' => $request->reference,
            'terms_and_conditions' => $request->terms_and_conditions,
            'discount' => $request->discount,
            'customer_id' => $request->customer_id,
        ]);

        $idsToKeep = collect($request->invoice_items)->pluck('id')->toArray();
        InvoiceItem::where('invoice_id', $invoice->id)->whereNotIn('id', $idsToKeep)->delete();

        foreach ($request->invoice_items as $item) {
            InvoiceItem::updateOrCreate(
                ['id' => $item['id'] ?? null],
                [
                    'invoice_id' => $invoice->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price']*100,
                ]
            );
        }

        return back()->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return back();
    }

    public function downloadPDF(Request $request, Invoice $invoice)
    {
        $invoice->load('invoice_items.material.material_unit_size', 'invoice_items.material.material_category');
        $data = [
            'invoice' => InvoiceResource::make($invoice),
            'company' => CompanyResource::collection(Company::all())->first(),
        ];
        $pdf = Pdf::loadView('pdf.invoice-pdf', $data);
        return $pdf->download('Invoice.pdf');
    }

    public function browserPDF(Request $request, Invoice $invoice)
    {
        $invoice->load('invoice_items.material.material_unit_size', 'invoice_items.material.material_category');

        $data = [
            'invoice' => InvoiceResource::make($invoice),
            'company' => CompanyResource::collection(Company::all())->first(),
        ];

        $pdf = Pdf::loadView('pdf.invoice-pdf', $data);
        return $pdf->stream('invoice-in-browser.pdf');
    }
}
