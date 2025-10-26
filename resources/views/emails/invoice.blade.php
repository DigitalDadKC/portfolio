
<body style="padding: 20px 0; font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif">
    <h1 style="font-family: Brush">
        Hello There {{ $invoice['client']['company'] }}!
    </h1>

    <div>
        {{ $invoice['client']['email'] }}
    </div>

    <div>
        <a method="post" href="{{ $checkout_session->url }}" >Pay your invoice now</a>
    </div>

    <div>
        {{ $company['name'] }}

        <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="30" />
    </div>

    <div style="font-style: italic; color: #77685D">
        <p style="padding: 0; margin: 0;">
            <a href="https://digitaldadkc.com" aria-label="Visit my site">
                <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="30" />
            </a>
        </p>
        <img src="{{ asset('/img/dad.svg') }}" alt="logo" id="image" width="30" />
        <img src="{{ asset('img/dad.png') }}" alt="logo" id="image" width="30" />
        <img src="{{ asset('img/dad.svg') }}" alt="logo" id="image" width="30" />
        <p style="padding: 0; margin: 0;">RALEIGH GROESBECK</p>
        <p style="padding: 0; margin: 0">Owner</p>
        <p style="padding: 0; margin: 0">Digital Dad, LLC</p>
        <p id="line-break" style="font-weight: bold; color: #ac6b34; padding: 0; margin: 0;">____________________________</p>
        <p style="padding: 0; margin: 0;">Portfolio:
            <a href="https://digitaldadkc.com" aria-label="Visit my site">digitaldadkc.com</a>
        </p>
        <p style="padding: 0; margin: 0;">Email:
            <a href="mailto:raleighgroesbeck@gmail.com" aria-label="Email Me">raleighgroesbeck@gmail.com</a>
        </p>
        <p style="padding: 0; margin: 0;">Schedule a meeting:
            <a href="https://calendly.com/digitaldadkc" aria-label="Email Me">Calendly</a>
        </p>
    </div>
</body>
