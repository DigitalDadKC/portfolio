
<body style="font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif; background-color: #F3EEEA; width: 100%; padding: 2rem 0;">
    <div style="width: 100%;">
        <div style="padding: 0 2rem; display: flex;">
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
                    Invoice # {{ $invoice['number'] }}
                </h2>
                <div>
                    Date Created: {{ $invoice['date_created'] }}
                </div>
                <div>
                    Due Date: {{ $invoice['due_date'] }}
                </div>
                <div>
                    Bill to: {{ $invoice['client']['company'] }}
                </div>
                <div>
                    {{ $invoice['client']['email'] }}
                </div>
            </div>
        </div>

        {{-- @php
            $total = 0;
        @endphp --}}

        {{-- @foreach($invoice['client_invoice_items'] as $item)
            @php
                $item_total = $item['price']*$item['quantity'];
                $total += $item_total;
            @endphp
        @endforeach --}}

        <div style="text-align: center; background-color: #EBE3D5; padding: 2rem 0">
            {{-- <h3>
                Total: {{"$ " . number_format($total, 0, ",", ",")  }}
            </h3> --}}
            <button style="background-color: #776B5D; border-radius: 0.5rem; text-shadow: 1px">
                <a style="padding: 1rem; color: #F3EEEA;" method="post" href="{{ $checkout_session->url }}" aria-label="Pay Now">
                    PAY NOW
                </a>
            </button>
        </div>

        <div style="font-style: italic; color: #77685D; padding: 2rem">
            <a href="https://digitaldadkc.com" aria-label="Visit my site">
                <img src="{{ asset('/img/dad.svg') }}" alt="logo" id="image" width="30" />
            </a>
            <div>
                RALEIGH GROESBECK
            </div>
            <div>
                Owner & Developer
            </div>
            <div>
                Digital Dad, LLC
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
