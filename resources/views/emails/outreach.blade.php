
<body style="padding: 20px 0; font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif">
    <h1 style="font-family: Brush">
        Hello There {{ $company }}!
    </h1>
    <div>
        <p style="margin: 0;">Let's be honest, nobody ENJOYS working with spreadsheets all day</p>
        <p>I am a local web app developer in the construction industry specializing in transforming complex, time-consuming spreadsheets into <span style="font-weight: bold;">powerful, sophisticated web apps</span> from the ground up for businesses just like yours, tailored to fit their needs.</p>
    </div>

    <div>
        <p>Whether it's tracking inventory, managing clients, calculating budgets, or automating reports, I take your existing Excel or Google Sheets workflows and turn them into a custom web platform that's</p>
    </div>

    <div style="margin-bottom: 16px;">
        <ul>
            <li>
                <img src="{{ $message->embed('img/check.png') }}" alt="checkmark" width="30" />
                Full-cycle construction software including custom estimate-building tools, email notifications, and custom exports (PDF/Excel).
            </li>
            <li>
                <img src="{{ $message->embed('img/check.png') }}" alt="checkmark" width="30" />
                Accessible anywhere - no more emailing files back and forth.
            </li>
            <li>
                <img src="{{ $message->embed('img/check.png') }}" alt="checkmark" width="30" />
                Error-proof - built-in data validation and user permissions.
            </li>
            <li>
                <img src="{{ $message->embed('img/check.png') }}" alt="checkmark" width="30" />
                Visually clear - dashboards, charts, and insights at a glance.
            </li>
        </ul>

        <div>
            <p>I specialize in working locally with small businesses and teams who want to modernize without losing the logic and workflows they've already built.</p>
            <p>
                I'd love to learn more about {{ $company }} and share a couple of ideas on how a web app could save time and create new opportunities for growth. Would you be open to slot in a 15-minute phone call to your schedule?
            </p>
        </div>
    </div>

    <div id="outro" style="font-style: italic; color: #77685D">
        <p style="padding: 0; margin: 0;">
            <a href="https://digitaldadkc.com" aria-label="Visit my site">
                <img src="{{ $message->embed('img/dad.png') }}" alt="logo" id="image" width="30" />
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
