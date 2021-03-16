<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>JeVeuxAider | Devenez bénévole et trouvez des missions en quelques clics</title>
    <script>
        (function (i, s, o, g, r, a, m) {
        i["ApiEngagementObject"] = r; (i[r] = i[r] || function () { (i[r].q = i[r].q || []).push(arguments); }), (i[r].l = 1 * new Date()); (a = s.createElement(o)), (m = s.getElementsByTagName(o)[0]); a.async = 1; a.src = g; m.parentNode.insertBefore(a, m);
        })(window, document, "script", "https://api-engagement.beta.gouv.fr/jstag.js", "apieng");
        apieng("config", "5f5931496c7ea514150a818f");
    </script>
    <link rel="icon" href="{{ asset("/images/favicon/favicon.png") }}" />
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
    <script type="text/javascript" src="{{ mix('/assets/js/app.js') }}"></script>
</body>

</html>
