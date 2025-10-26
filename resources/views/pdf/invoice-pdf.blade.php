<div>
    <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="30" />
</div>

<div>
    {{ $clientInvoice->id }}
</div>

<div>
    {{ $clientInvoice->number }}
</div>

<div>
    {{ $clientInvoice->date_created }}
</div>


<div>
    {{ $clientInvoice->due_date }}
</div>


<div>
    {{ $clientInvoice->terms_and_conditions }}
</div>

<div>
    {{ $clientInvoice->client_id }}
</div>

<div>
    {{ $clientInvoice->client->company }}
</div>

<div>
    {{ $clientInvoice->client->email }}
</div>

<div>
    <form action="{{ route('checkout') }}" method="POST">
        @csrf
        pay over here!
        <button type="submit">
            Checkout with Stripe
        </button>
    </form>
</div>
