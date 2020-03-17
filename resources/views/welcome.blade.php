<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Covid</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div style="background-image: url(images/bg_header_home.jpg)" class="py-12">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-12">
                <img id="logo-etat" src="/images/logo-header.svg" alt="" style="height: 42px" />
                <a href="/"><img id="logo-rc" src="/images/logo-reserve-civique_light.svg" alt="" style="height: 47px" /></a>
                <a href="https://reserve-civique.beta.gouv.fr" target="_blank" class="text-white text-sm font-thin">Béta</a>
            </div>
            <div class="my-8 text-4xl text-white max-w-4xl mx-auto text-center">
                <h1>Face à l’épidémie de <span class="italic font-bold">Covid-19</span> le Gouvernement appelle à une mobilisation de toutes les bonnes volontés.</h1>
            </div>
            <div class="flex justify-center">
                <a href="/" class="btn-primary m-3">
                    <span class="uppercase font-light text-xs">Je suis une structure publique ou associative</span>
                    <span>J'ai besoin d'aide</span>
                </a>
                <a href="#" class="btn-secondary m-3" style="min-width: 300px">
                    <span class="uppercase font-light text-xs">Je suis volontaire</span>
                    <span>Je veux aider</span>
                </a>
            </div>
        </div>
    </div>
    <div class="flex">
        <div class="w-1/2"><img class="" src="/images/brainstorming.jpeg" alt="" /></div>
        <div class="w-1/2 p-12">
            <div class="pr-32">
                <h2 class="text-4xl mb-3 text-black font-bold">Votre structure est engagée dans la crise sanitaire face au Covid 19 ?</h2>
                <p class="py-3 text-gray-600">Identifiez votre besoin et partagez-le sur la Réserve Civique pour que des citoyens vous viennent en aide dans le respect des règles de sécurité et des directives du gouvernement.</p>
            </div>
        </div>
    </div>
    <div class="flex">
        <div class="w-1/2 p-12">
            <div class="pl-32">
                <h2 class="text-4xl mb-3 text-black font-bold">Vous souhaitez rejoindre les volontaires ?</h2>
                <p class="py-3 text-gray-600">Aidez un travailleur en première ligne vivant proche de chez vous en gardant un enfant ou en faisant des courses, et engagez-vous dans la lutte contre l’épidémie.</p>
            </div>
        </div>
        <div class="w-1/2"><img class="" src="/images/volontaires.jpeg" alt="" /> </div>
    </div>
</body>

</html>