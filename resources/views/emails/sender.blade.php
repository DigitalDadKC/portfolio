<body style="padding: 20px 0; font-size: 16px; font-family: Perpetua, 'Times New Roman', Times, serif">
    <h1>Thanks {{$name}}!</h1>
    <p>Here's a copy of your message</p>

    <div id="body" style="margin-bottom: 20px;">
        "{{ $body }}""
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

<style>

    a:link {
        color: #776B5D;
        margin: 0;
    }

    a:hover {
        color: #B0A695;
    }

    a:visited {
        color: #776B5D;
        margin: 0;
    }

    #outro {
        font-weight: bold;
        margin-top: 40px;
        font-size: 10px;
    }
</style>
