<body>
    <h1>Thanks {{$name}}!</h1>
    <p>Here's a copy of your message</p>
    <div id="body">
        "{{ $body }}""
    </div>

    {{-- <div id="meeting">
        <p class="padding: 0; margin: 0;">Don't forget to schedule a meeting!</p>
        <a href="https://calendly.com/digitaldadkc" aria-label="Email Me">https://calendly.com/digitaldadkc</a>
    </div> --}}

    <div id="outro">
        <p><a href="https://www.raleighgroesbeck.com" aria-label="Visit my site"><img src="{{ asset('img/dad.png') }}" alt="logo" id="image" width="10" /></a></p>
        <p>RALEIGH GROESBECK</p>
        <p>Owner</p>
        <p id="line-break">____________________________</p>
        <p>Website: <a href="https://www.raleighgroesbeck.com" aria-label="Visit my site">www.raleighgroesbeck.com</a></p>
        <p>Email: <a href="mailto:raleighgroesbeck.com" aria-label="Email Me">raleighgroesbeck@gmail.com</a></p>
        <p>Schedule a meeting: <a href="https://calendly.com/digitaldadkc" aria-label="Email Me">Calendly</a></p>
    </div>
</body>

<style>
    body {
        font-family: Georgia;
    }

    #body {
        padding: 20px 0;
    }

    #meeting {
        margin: 0;
        font-size: 12px;
    }

    #image {
        width: 40px;
    }

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

    #line-break {
        font-weight: bold;
        color: #ac6b34;
    }

    #outro {
        color: #77685D;
        font-weight: bold;
        margin-top: 40px;
        font-style: italic;
        font-size: 10px;
    }
</style>
