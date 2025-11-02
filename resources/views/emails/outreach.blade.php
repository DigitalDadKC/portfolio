
<body style="padding: 20px 0; font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif">
    <h1 style="font-family: Brush">
        Hello There {{ $company }}!
    </h1>
    <div>
        <p style="margin: 0;">How does {{$company}}'s current software stack up?</p>
        <p style="margin: 0;">Are you stuck in spreadsheet chaos?</p>
        <p>I am a local web app developer in the construction industry specializing in transforming spreadsheets into <span style="font-weight: bold;">beautiful, sophisticated custom web apps</span> from the ground up for businesses just like yours, tailored to fit their needs.</p>
    </div>

    <div style="margin-bottom: 16px;">
        <p>A few examples of what I specialize in...</p>
        <ul>
            <li>
                Full-cycle construction software including bid scheduling, intelligent estimate-building module, email notifications, and custom exports (PDF/Excel).
            </li>
            <li>
                Secure portals for staff, including roles and permissions, custom dashboards, and administrative panels.
            </li>
            <li>
                Convert spreadsheets into BREAD-style datatables (Browse, Read, Edit, Update, Delete) with filters and custom sorting.
            </li>
        </ul>

        <div>
            <p>
                If you’ve ever found yourself wishing your current operations or tools could “just do more,” that’s where I come in.
            </p>
            <p>
                I’d love to learn more about {{ $company }} and share a couple of ideas on how a web app could save time and create new opportunities for growth. Would you be open to slot in a 15-minute phone call to your schedule?
            </p>
        </div>
    </div>

    <div id="outro" style="font-style: italic; color: #77685D">
        <p style="padding: 0; margin: 0;">
            <a href="https://digitaldadkc.com" aria-label="Visit my site">
                <img src="{{ asset('/img/dad.png') }}" alt="logo" id="image" width="30" />
            </a>
        </p>
        <p style="padding: 0; margin: 0;">RALEIGH GROESBECK</p>
        <p style="padding: 0; margin: 0">Owner</p>
        <p style="padding: 0; margin: 0">Digital Dad, LLC</p>
        <p id="line-break" style="font-weight: bold; color: #ac6b34; padding: 0; margin: 0;">____________________________</p>
        <p style="padding: 0; margin: 0;">Portfolio:
            <a href="https://digitaldadkc.com" aria-label="Visit my site">digitaldadkc.com</a>
        </p>
        <p style="padding: 0; margin: 0;">Email:
            <a href="mailto:info@digitaldadkc.com" aria-label="Email Me">info@digitaldadkc.com</a>
        </p>
        <p style="padding: 0; margin: 0;">Schedule a meeting:
            <a href="https://calendly.com/digitaldadkc" aria-label="Email Me">Calendly</a>
        </p>
    </div>
</body>
