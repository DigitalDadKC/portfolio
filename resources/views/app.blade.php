<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="http://localhost:8000/img/dad.png">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased" style="margin-bottom: 0;">
        @inertia
        {{-- <div
            class="calendly-inline-widget"
            data-url="https://calendly.com/digitaldadkc/30min"
            style="min-width:320px;height:700px;">
        </div> --}}
    </body>
</html>

{{-- <script
     type="text/javascript"
     src="https://assets.calendly.com/assets/external/widget.js"
     async>
</script> --}}
