
<div style="font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif; background-color: #F3EEEA; width: 100%; padding: 2rem 0.5rem;">
    <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="50" style="padding: 20px;" />
    <div style="background-color: #B0A695; text-align: center; padding: 4px 0">
        <h1>Freelance Contract</h1>
    </div>

    <p>This Freelance Software Development Contract ("Contract") is made effective as of [Date], by and between {{ $contract['client']['company'] }}, located at {{ $contract['client']['address'] }}, {{ $contract['client']['city'] }}, {{ $contract['client']['state']['abbr'] }}, {{ $contract['client']['zip'] }} ("Client"), and DigitalDad, LLC ("Developer").</p>

    <ol>
        <li>
            <h4 class="list-header">Services to be rendered</h4>
            <ul class="list">
                <li>The Developer agrees to provide the following services ("Services"):</li>
                <li>
                    @foreach($contract['services'] as $service)
                        <p>{{ $service['name'] }}</p>
                    @endforeach
                </li>
            </ul>
        </li>
        <li>
            <h4 class="list-header">Payment</h4>
            <ul class="list">
                <li class="list-item">2.1 - Fees: The Client agrees to pay the Developer a fixed fee of <b style="text-decoration: underline">${{ number_format($contract['price'], 2) }}</b>.</li>
                <li class="list-item">2.2 - Payment Schedule: Payments shall be made according to the following schedule: Upon project completion</li>
                <li class="list-item">2.3 - Invoicing: The Developer shall submit invoices to the Client on upon project completion, and payment is due within 30 days of receipt of the invoice.</li>
            </ul>
        </li>
        <li>
            <h4 class="list-header">Cancellation</h4>
            <ul class="list">
                <li class="list-item">3.1 - Terms: This Contract shall commence on the effective date and shall continue until completion of the Services or until terminated by either party.</li>
                <li class="list-item">3.2 - Cancellation: Either party may terminate this Contract with 14 days written notice to the other party. In the event of termination, the Client shall pay the Developer for all Services performed up to the date of termination.</li>
            </ul>
        </li>
        <li>
            <h4 class="list-header">Intellectual Property</h4>
            <ul class="list">
                <li class="list-item">4.1 - Ownership: The Client shall own all rights, title, and interest in and to the work product created by the Developer under this Contract. The Developer agrees to assign all rights in the work product to the Client upon full payment.</li>
                <li class="list-item">4.2 - License: The Developer retains the right to use the work product for portfolio purposes, unless otherwise agreed in writing.</li>
            </ul>
        </li>
        <li class="page-break">
            <h4 class="list-header">Confidentiality</h4>
            <ul class="list">
                <li class="list-item">5.1 - The Developer agrees to keep confidential all information related to the Client's business, operations, and projects, and will not disclose such information to any third parties without the Client’s prior written consent.</li>
            </ul>
        </li>
        <li>
            <h4 class="list-header">Warranties & Representation</h4>
            <ul class="list">
                <li class="list-item">6.1 - The Developer represents and warrants that
                    <ol>
                        <li>Services will be performed in a professional manner</li>
                        <li>The work product will be original and will not infringe upon any third-party rights.</li>
                    </ol>
                </li>
            </ul>
        </li>
        <li>
            <h4 class="list-header">Limitation of Liability</h4>
            <ul class="list">
                <li class="list-item">7.1 - In no event shall either party be liable for any indirect, incidental, special, or consequential damages arising out of this Contract, even if advised of the possibility of such damages.</li>
            </ul>
        </li>
        <li>
            <h4 class="list-header">Governing Law</h4>
            <ul class="list">
                <li class="list-item">8.1 - This Contract shall be governed by and construed in accordance with the laws of the state of Missouri.</li>
            </ul>
        </li>
        <li>
            <h4 class="list-header">Agreement</h4>
            <ul class="list">
                <li class="list-item">9.1 - This Contract constitutes the entire agreement between the parties and supersedes all prior negotiations and agreements, whether written or oral.</li>
            </ul>
        </li>
    </ol>

    <div class="signature">
        <div class="signature-section">
            <p class="signature-line">Client: ___________________________________________________</p>
            <p class="signature-line">Date: _________________________</p>
        </div>

        <div class="signature-section">
            <p class="signature-line">Developer: ___________________________________________________</p>
            <p class="signature-line">Date: _________________________</p>
        </div>
    </div>
</div>

<style>
    .list-header {
        margin: 0;
    }
    .list {
        list-style-type: none;
    }
    .list-item {
        margin-bottom: 1rem;
    }
    .page-break {
         page-break-before: always;
    }
    .signature {
        width: 100%;
        position: absolute;
        bottom: 0;
        background-color: #EBE3D5;
    }
    .signature-section {
        padding: 2rem;
    }
    .signature-line {
        font-weight: bold;
        color: #ac6b34;
    }
    .signature-label {
        font-weight: bold;
    }
    .signature-label-company {
        font-style: italic;
    }

</style>
