<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>DigitalDad Contract</title>

        <style>
            html, body {
                height: 100%;
            }

            div {
                font-family: 'Trebuchet MS', sans-serif;
            }
        </style>
    </head>

    <body>
        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    {{-- <img src={{ asset('/storage/' . (optional($company)->logo)) }} alt="company logo" width="200" /> --}}
                    {{-- <img src="{{ $message->embed('img/dad.png') }}" alt="logo" id="image" width="30" /> --}}
                    <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="30" />
                </td>
                <td style="width: 25%;">
                    <div><h2 style="margin: 0; color: #3d4dfc;">INVOICE</h2></div>
                    {{-- <div style="margin-top: 1.25rem;">Date Created: {{$invoice->date_created}}</div>
                    <div>Invoice #{{$invoice->number}}</div>
                    <div>Customer ID: {{$invoice->customer->id}}</div>
                    <div>Due Date: {{$invoice->due_date}}</div> --}}
                </td>
            </tr>
        </table>

        <div style="margin-top: 1.25rem">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%">
                        {{-- <div><h3 style="margin: 0;">FROM:</h3></div>
                        <div>{{$company->name}}</div>
                        <div>{{$company->address}}</div>
                        <div>{{$company->city}} {{$company->state->abbr}}, {{$company->zip}}</div> --}}
                    </td>
                    <td>
                        {{-- <div><h3 style="margin: 0;">TO:</h3></div>
                        <div>{{ $invoice->customer->name }}</div>
                        <div>{{ $invoice->customer->address }}</div>
                        <div>{{ $invoice->customer->city }}, {{ $invoice->customer->state->abbr }} {{ $invoice->customer->zip }}</div> --}}
                    </td>
                </tr>
            </table>
        </div>

        @php
            $subtotal = 0;
        @endphp

        <div style="margin-top: 1.25rem;">
            <table style="width: 100%; border-spacing: 0">
                <tr style="background-color: #B0A695;">
                    <th style="color: #F3EEEA; padding: 0.5rem">SKU</th>
                    <th style="color: #F3EEEA; padding: 0.5rem">Description</th>
                    <th style="color: #F3EEEA; padding: 0.5rem">Units</th>
                    <th style="color: #F3EEEA; padding: 0.5rem">Price</th>
                    <th style="color: #F3EEEA; padding: 0.5rem">Quantity</th>
                    <th style="color: #F3EEEA; padding: 0.5rem">Total</th>
                </tr>
                {{-- @foreach($invoice->invoice_items as $item) --}}
                    <tr style="background-color: #EBE3D5">
                        {{-- <td style="padding: 0.5rem">{{$item->material->sku}}</td>
                        <td style="padding: 0.5rem">{{$item->material->name}}</td>
                        <td style="padding: 0.5rem">{{$item->material->material_unit_size->Unit_Size}}</td>
                        <td style="padding: 0.5rem">${{number_format($item->unit_price/100, 2)}}</td>
                        <td style="padding: 0.5rem">{{$item->quantity}}</td>
                        <td style="padding: 0.5rem; text-align: right;">${{number_format($item->unit_price/100 * $item->quantity, 2)}}</td> --}}
                    </tr>
                    @php
                        // $subtotal += number_format(num: $item->unit_price/100 * $item->quantity, 2)
                    @endphp
                {{-- @endforeach --}}
            </table>
        </div>

        <div style="text-align: right; margin-top: 1rem; font-size: 0.875rem">
            {{-- <p>Subtotal: ${{ $subtotal }} USD</p>
            <p>Discount {{$invoice->discount}}%</p>
            <p>Total: ${{ number_format($subtotal * (1 - $invoice->discount/100), 2) }} USD</p> --}}
        </div>

        <div style="margin-top: 1.25rem;">
            <table style="width: 100%; border-spacing: 0">
                <tr style="background-color: #B0A695;">
                    <th style="color: #F3EEEA; padding: 0.5rem">Terms & Conditions</th>
                </tr>
                <tr>
                    {{-- <td>{{ $invoice->terms_and_conditions }}</td> --}}
                </tr>
            </table>
        </div>

        <div style="position: fixed; bottom: 0; width: 100%; padding: 1rem 0.75rem; background-color: #EBE3D5">
            {{-- <div style="text-align: right;">&copy; {{$company->name}}</div> --}}
        </div>
    </body>
</html>
