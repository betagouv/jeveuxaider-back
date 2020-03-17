<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>COVID</title>
        <link href="{{ mix("/assets/css/app.css") }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="app"></div>
        <script type="text/javascript" src="{{ mix('/assets/js/app.js') }}"></script>
    </body>
</html>
