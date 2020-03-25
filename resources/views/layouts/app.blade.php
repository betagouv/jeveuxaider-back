<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réserve Civique | Engagez-vous face à l'épidémie de Covid-19</title>
    <link rel="icon" href="{{ asset("/images/favicon.jpg") }}" />
    <link href="{{ mix("/assets/css/app.css") }}" rel="stylesheet" type="text/css">
    <script src="//tag.aticdn.net/610648/smarttag.js"></script>
</head>

<body>
    <div id="app"></div>
    <script type="text/javascript">
        var tag = new ATInternet.Tracker.Tag();
        tag.page.set({});
        tag.dispatch();
    </script>
    <script>
        Userback = window.Userback || {};
        Userback.access_token = '8654|15306|z7JRFuJkExuIVFcztrli1HqKOnGVSikUhotmIJl6M1x3tJKtaW';
        (function(id) {
            var s = document.createElement('script');
            s.async = 1;
            s.src = 'https://static.userback.io/widget/v1.js';
            var parent_node = document.head || document.body;
            parent_node.appendChild(s);
        })('userback-sdk');
    </script>
    <script type="text/javascript" src="{{ mix('/assets/js/app.js') }}"></script>
</body>

</html>