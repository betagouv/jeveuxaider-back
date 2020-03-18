<template>
  <div>
    <div style="background-image: url(images/bg_header_home.jpg)" class="py-12">
      <div class="container mx-auto px-4">
        <div class="lg:flex lg:justify-between text-center pb-6">
          <div class="my-3">
            <img class="mx-auto" src="/images/logo-header.svg" alt style="height: 42px" />
          </div>
          <div class="my-3">
            <router-link to="/">
              <img
                class="mx-auto"
                src="/images/logo-reserve-civique_light.svg"
                alt="Réserve Civique"
                style="height: 47px"
              />
            </router-link>
          </div>
          <div class="my-3">
            <dropdown-user v-if="$store.getters.isLogged"></dropdown-user>
            <router-link
              v-else
              to="/user/login"
              class="mx-auto text-white text-sm font-thin hover:underline"
            >Se connecter</router-link>
          </div>
        </div>
        <div class="py-8 text-2xl lg:text-4xl text-white max-w-4xl mx-auto text-center">
          <h1>
            Face à l’épidémie de
            <span class="italic font-bold">Covid-19</span> le Gouvernement appelle à une mobilisation de toutes les bonnes volontés.
          </h1>
        </div>
        <div class="flex flex-col lg:flex-row items-center justify-center">
          <router-link
            to="/user/register/responsable"
            class="btn-primary m-3 w-full"
            style="max-width:320px;"
          >
            <span
              class="uppercase font-light text-xxs"
            >Je suis une structure publique ou associative</span>
            <span class="text-lg font-bold">J'ai besoin d'aide</span>
          </router-link>
          <router-link
            to="/user/register/volontaire"
            class="btn-secondary m-3 w-full"
            style="max-width:320px;"
          >
            <span class="uppercase font-light text-xxs">Je suis volontaire</span>
            <span class="text-lg font-bold">Je veux aider</span>
          </router-link>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="lg:w-1/2">
        <img class="object-cover object-center w-full h-full" src="/images/brainstorming.jpeg" alt />
      </div>
      <div class="lg:w-1/2 p-4 sm:p-8 md:p-16 lg:flex lg:justify-start">
        <div class="lg:max-w-xl">
          <h2
            class="titre-1"
          >Votre structure est engagée dans la crise sanitaire face au Covid 19&nbsp;?</h2>
          <p
            class="py-3 text-gray-600 text-lg"
          >Identifiez votre besoin et partagez-le sur la Réserve Civique pour que des citoyens vous viennent en aide dans le respect des règles de sécurité et des directives du gouvernement.</p>
          <div class="mt-3">
            <router-link to="/user/register/responsable" class="btn-primary">Proposer des missions</router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="lg:w-1/2 p-4 sm:p-8 md:p-16 lg:flex lg:justify-end">
        <div class="lg:max-w-xl">
          <h2 class="titre-1">Vous souhaitez rejoindre les volontaires&nbsp;?</h2>
          <p
            class="py-3 text-gray-600 text-lg"
          >Aidez un travailleur en première ligne vivant proche de chez vous en gardant un enfant ou en faisant des courses, et engagez-vous dans la lutte contre l’épidémie.</p>
          <div class="py-3">
            <router-link to="/missions" class="btn-secondary">Trouver une mission</router-link>
          </div>
          <div class="pt-3">
            <router-link
              to="/regles-de-securite"
              class="text-15 text-gray-600 hover:underline"
            >Cet engagement nécessite un respect strict des règles sanitaires applicables&nbsp;›</router-link>
          </div>
        </div>
      </div>
      <div class="lg:w-1/2">
        <img class="object-cover object-center w-full h-full" src="/images/volontaires.jpeg" alt />
      </div>
    </div>

    <div class="bg-gray-100">
      <div class="container mx-auto px-4">
        <div class="pt-8 md:pt-12 lg:pt-24 text-center">
          <h2 class="titre-1">Les partenaires de la Réserve Civique</h2>
          <div
            class="text-gray-600 text-xl mb-12"
          >Collectivités, plateformes de l'engagement et spécialistes du bénévolat, rejoignez le mouvement !</div>
        </div>
        <hr class="text-gray-600 py-4 w-24 border-t-4 mx-auto" />
        <div class="flex flex-col lg:flex-row flex-wrap pb-8 md:pb-12">
          <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
            <div class="text-gray-800 text-sm uppercase font-bold mb-2">WEBASSOC</div>
            <div class="text-gray-600 text-lg">Aide aux associations à se renforcer avec internet</div>
          </div>
          <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
            <div class="text-gray-800 text-sm uppercase font-bold mb-2">Tous Bénévoles</div>
            <div
              class="text-gray-600 text-lg"
            >Mise en relation des associations et des candidats bénévoles</div>
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
            <div
              class="text-gray-600 text-lg"
            >Le site pour aider les personnes qui luttent contre le coronavirus</div>
          </div>
          <div class="w-full lg:max-w-1/3 p-3 text-center flex justify-center items-center">
            <a href="#" class="btn-primary">Rejoindre les partenaires</a>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="bg-blue-700 text-white lg:w-1/2 px-4 py-8 md:px-8 md:py-12 lg:px-12 lg:py-16">
        <div
          class="lg:max-w-xl flex flex-col items-center text-center lg:items-start lg:text-left lg:ml-auto"
        >
          <h2 class="titre-1 text-white">Informations Coronavirus</h2>
          <p
            class="py-3 text-lg"
          >Toutes les réponses aux questions que vous vous posez sur le Coronavirus COVID-19</p>
          <div class="mt-3">
            <a
              href="https://www.gouvernement.fr/info-coronavirus"
              target="_blank"
              class="btn-secondary plain"
            >S'informer</a>
          </div>
        </div>
      </div>
      <div class="lg:w-1/2 flex items-center">
        <img
          class="object-cover object-center w-full h-full"
          src="/images/covid_19_header.jpg"
          style="max-height:300px;"
        />
      </div>
    </div>
    <AppFooter />
  </div>
</template>