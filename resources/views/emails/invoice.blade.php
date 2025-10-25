
<body style="padding: 20px 0; font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif">
    <h1 style="font-family: Brush">
        Hello There {{ $invoice['client']['company'] }}!
    </h1>

    <h6>
        {{ $invoice['total_price'] }}
    </h6>

    <div>
        {{ $invoice['client']['email'] }}
    </div>

    <div>
        <a method="post" href="{{ $checkout_session->url }}" >Pay your invoice now</a>
    </div>

    <div>
        {{ $company['name'] }}
    </div>

    <div>
        <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="30" />
    </div>
</body>
