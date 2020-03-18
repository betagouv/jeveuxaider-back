<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Covid</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,600,700" rel="stylesheet">
    <link href="{{ mix('/assets/css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app-blade">
        <vue-header></vue-header>

        <div class="bg-blue-900 pb-32">
            <div class="container mx-auto px-4">
                <div class="pt-10">
                    <h1 class="text-3xl font-bold text-white">@yield('page_title')</h1>
                </div>
            </div>
        </div>

        <div class="-mt-32">
            <div class="container mx-auto px-4 my-12">
                <div class="bg-white rounded-lg shadow-lg {{ $content_padding ?? 'px-4 py-8 sm:p-8 lg:p-12 xl:p-16' }}">
                    @yield('content')
                </div>
            </div>
        </div>

        @include('components.footer')
    </div>

    <script type="text/javascript" src="{{ mix('/assets/js/app-blade.js') }}"></script>

</body>

</html>
