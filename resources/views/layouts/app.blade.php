<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réserve Civique | Engagez-vous face à l'épidémie de Covid-19</title>
    <link rel="icon" href="{{ asset("/images/favicon.jpg") }}" />
    <link href="{{ mix("/assets/css/app.css") }}" rel="stylesheet" type="text/css">
    <script src="//tag.aticdn.net/610648/smarttag.js"></script>
    <script type="text/javascript">
    Crisp = window.Crisp || {};
    window.$crisp=[];
    window.CRISP_WEBSITE_ID="4b843a95-8a0b-4274-bfd5-e81cbdc188ac";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
    </script>
    
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