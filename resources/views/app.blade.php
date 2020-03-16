<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>COVID</title>
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
        <link href="{{ mix("/assets/css/app.css") }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="app">
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
        <script type="text/javascript" src="{{ mix('/assets/js/app.js') }}"></script>
    </body>
</html>
