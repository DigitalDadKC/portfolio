
<div style="font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif; background-color: #F3EEEA; width: 100%; padding: 2rem 0.5rem;">
    <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="50" />
    <div style="background-color: #B0A695; text-align: center; padding: 4px 0">
        <h1>Freelance Contract</h1>
    </div>

    <p>This Freelance Software Development Contract ("Contract") is made effective as of [Date], by and between {{ $contract['client']['company'] }}, located at {{ $contract['client']['address'] }}, {{ $contract['client']['city'] }}, {{ $contract['client']['state']['abbr'] }}, {{ $contract['client']['zip'] }} ("Client"), and DigitalDad, LLC ("Developer").</p>

    <ol>
        <li>
            <h4 style="margin: 0;">Services to be rendered</h4>
            <ul style="list-style-type: none;">
                <li>The Developer agrees to provide the following services ("Services"):</li>
                <li>
                    @foreach($contract['services'] as $service)
                        <p>{{ $service['name'] }}</p>
                    @endforeach
                </li>
            </ul>
        </li>
        <li>
            <h4 style="margin: 0;">Payment</h4>
            <ul style="list-style-type: none;">
                <li>2.1 Fees: The Client agrees to pay the Developer a fixed fee of <b style="text-decoration: underline">${{ number_format($contract['price'], 2) }}</b>.</li>
                <li>2.2 Payment Schedule: Payments shall be made according to the following schedule: Upon project completion</li>
                <li>2.3 Invoicing: The Developer shall submit invoices to the Client on upon project completion, and payment is due within 30 days of receipt of the invoice.</li>
            </ul>
        </li>
    </ol>

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
</div>
