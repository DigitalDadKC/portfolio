
<div style="font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif; background-color: #F3EEEA; width: 100%; padding: 2rem 0;">
    <div style="width: 100%;"></div>
        <div style="padding: 0 2rem; display: flex; margin-bottom: 4rem;">
            <div style="width: 50%;">
                <a href="https://digitaldadkc.com" aria-label="Visit my site">
                    <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="100" />
                </a>
                <div>
                    <div>
                        DigitalDad, LLC
                    </div>
                    <div>
                        <a href="mailto:info@digitaldadkc.com" aria-label="Email Me">info@digitaldadkc.com</a>
                    </div>
                </div>
            </div>
            <div style="padding: 0 2rem; text-align: right; margin-left: auto; width: 50%;">
                <h2>
                    Invoice # {{ $clientInvoice['number'] }}
                </h2>
                <div>
                    Date Created: {{ $clientInvoice['date_created'] }}
                </div>
                <div>
                    Due Date: {{ $clientInvoice['due_date'] }}
                </div>
                <div>
                    Bill to: {{ $clientInvoice['client']['company'] }}
                </div>
                <div>
                    {{ $clientInvoice['client']['email'] }}
                </div>
            </div>
        </div>

        @php
            $total = 0;
        @endphp

        <div style="margin-bottom: 20px; background-color: #EBE3D5; padding: 2rem">
            <table style="margin-bottom: 20px; width: 100%;">
                <thead>
                    <tr>
                        <th style="">
                            Description
                        </th>
                        <th style="">
                            Price
                        </th>
                        <th style="">
                            Quantity
                        </th>
                        <th style="text-align: right;">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientInvoice['client_invoice_items'] as $item)
                        <tr>
                            <td style="text-align: center;">
                                {{ $item['description'] }}
                            </td>
                            <td style="text-align: center;">
                                ${{ $item['price'] }}
                            </td>
                            <td style="text-align: center;">
                                {{ $item['quantity'] }}
                            </td>
                            <td style="text-align: right;">
                                ${{ $item['price'] * $item['quantity'] }}
                            </td>
                        </tr>
                        @php
                            $item_total = $item['price']*$item['quantity'];
                            $total += $item_total;
                        @endphp
                    @endforeach
                </tbody>
            </table>
            <div style="float: right;">
                Total: {{"$ " . number_format($total, 0, ",", ",")  }}
            </div>
        </div>

        <div>
            {{ $clientInvoice->terms_and_conditions }}
        </div>

        <div style="font-style: italic; color: #77685D; padding: 2rem">
            <a href="https://digitaldadkc.com" aria-label="Visit my site">
                <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="30" />
            </a>
            <div>
                RALEIGH GROESBECK
            </div>
            <div>
                Owner & Developer
            </div>
            <div>
                DigitalDad, LLC
            </div>
            <div>
                ____________________________
            </div>
            <div>
                Portfolio:<a href="https://digitaldadkc.com" aria-label="Visit my site">digitaldadkc.com</a>
            </div>
            <div>
                Email:<a href="mailto:info@digitaldadkc.com" aria-label="Email Me">mailto:info@digitaldadkc.com</a>
            </div>
            <div>
                Schedule a meeting:<a href="https://calendly.com/digitaldadkc" aria-label="Email Me">Calendly</a>
            </div>
        </div>
    </div>
</body>
