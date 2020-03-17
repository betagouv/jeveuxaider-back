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
        <div class="container mx-auto px-4">
            <div class="lg:flex lg:justify-between text-center pb-6">
                <div class="my-3">
                    <img class="mx-auto" src="/images/logo-header.svg" alt="" style="height: 42px" />
                </div>
                <div class="my-3">
                    <a href="/"><img class="mx-auto" src="/images/logo-reserve-civique_light.svg" alt="Réserve Civique" style="height: 47px" /></a></div>
                <div class="my-3">
                    <a class="mx-auto text-white text-sm font-thin hover:underline" href="https://reserve-civique.beta.gouv.fr" target="_blank">Béta</a>
                </div>
            </div>
            <div class="py-8 text-2xl lg:text-4xl text-white max-w-4xl mx-auto text-center">
                <h1>Face à l’épidémie de <span class="italic font-bold">Covid-19</span> le Gouvernement appelle à une mobilisation de toutes les bonnes volontés.</h1>
            </div>
            <div class="flex flex-col lg:flex-row justify-center">
                <a href="/" class="btn-primary m-3">
                    <span class="uppercase font-light text-xxs">Je suis une structure publique ou associative</span>
                    <span class="text-lg font-bold">J'ai besoin d'aide</span>
                </a>
                <a href="#" class="btn-secondary m-3">
                    <span class="uppercase font-light text-xxs">Je suis volontaire</span>
                    <span class="text-lg font-bold">Je veux aider</span>
                </a>
            </div>
        </div>
    </div>
    <div class="lg:flex">
        <div class="lg:w-1/2"><img class="" src="/images/brainstorming.jpeg" alt="" /></div>
        <div class="lg:w-1/2 p-12">
            <div class="lg:pr-24">
                <h2 class="text-2xl lg:text-4xl mb-3 text-gray-800 font-bold">Votre structure est engagée dans la crise sanitaire face au Covid 19 ?</h2>
                <p class="py-3 text-gray-600 text-lg">Identifiez votre besoin et partagez-le sur la Réserve Civique pour que des citoyens vous viennent en aide dans le respect des règles de sécurité et des directives du gouvernement.</p>
                <div class="mt-3">
                    <a href="/" class="btn-primary">
                        <span class="text-lg font-bold">Proposer des missions</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="lg:flex">
        <div class="lg:w-1/2 p-12">
            <div class="lg:pl-24">
                <h2 class="text-2xl lg:text-4xl mb-3 text-gray-800 font-bold">Vous souhaitez rejoindre les volontaires ?</h2>
                <p class="py-3 text-gray-600 text-lg">Aidez un travailleur en première ligne vivant proche de chez vous en gardant un enfant ou en faisant des courses, et engagez-vous dans la lutte contre l’épidémie.</p>
                <div class="py-3">
                    <a href="/" class="btn-secondary">
                        <span class="text-lg font-bold">Trouver une mission</span>
                    </a>
                </div>
                <div class="pt-3">
                    <a href="#" class="text-gray-600 hover:underline">Cet engagement nécessite un respect strict des règles sanitaires applicables ></a>
                </div>
            </div>
        </div>
        <div class="lg:w-1/2"><img class="" src="/images/volontaires.jpeg" alt="" /> </div>
    </div>
    <div class="bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="pt-12 lg:pt-24 text-center">
                <h2 class="text-2xl lg:text-4xl mb-3 text-gray-800 font-bold">Les partenaires de la Réserve Civique</h2>
                <div class="text-gray-600 text-xl mb-12">Collectivités, plateformes de l'engagement et spécialistes du bénévolat, rejoignez le mouvement !</div>
            </div>
            <hr class="text-gray-600 py-4 w-24 border-t-4 mx-auto">
            <div class="flex flex-col lg:flex-row flex-wrap">
                <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
                    <div class="text-gray-800 text-sm uppercase font-bold mb-2">WEBASSOC</div>
                    <div class="text-gray-600 text-lg">Aide aux associations à se renforcer avec internet</div>
                </div>
                <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
                    <div class="text-gray-800 text-sm uppercase font-bold mb-2">Tous Bénévoles</div>
                    <div class="text-gray-600 text-lg">Mise en relation des associations et des candidats bénévoles</div>
                </div>
                <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
                    <div class="text-gray-800 text-sm uppercase font-bold mb-2">France Bénévolat</div>
                    <div class="text-gray-600 text-lg">Promouvoir, valoriser et favoriser le bénévolat</div>
                </div>
                <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
                    <div class="text-gray-800 text-sm uppercase font-bold mb-2">Vendredi</div>
                    <div class="text-gray-600 text-lg">Des emplois partagés entre entreprise et association</div>
                </div>
                <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
                    <div class="text-gray-800 text-sm uppercase font-bold mb-2">En première ligne</div>
                    <div class="text-gray-600 text-lg">Le site pour aider les personnes qui luttent contre le coronavirus</div>
                </div>
                <div class="w-full lg:max-w-1/3 p-3 my-3 text-center flex justify-center items-center">
                    <a href="#" class="btn-primary">
                        <span class="text-lg font-bold">Rejoindre les partenaires</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>