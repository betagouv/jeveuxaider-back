<template>
  <div class="font-montserrat">
    <div style="background-image: url(images/bg_header_home.jpg)" class="py-12">
      <div class="container mx-auto px-4">
        <div class="lg:flex lg:justify-between text-center pb-6">
          <div class="my-3">
            <img
              class="mx-auto"
              src="/images/logo-header.svg"
              alt
              style="height: 42px"
            />
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
              to="/login"
              class="mx-auto text-white text-sm font-thin hover:underline"
              >Se connecter</router-link
            >
          </div>
        </div>
        <div
          class="py-8 text-2xl lg:text-4xl text-white max-w-4xl mx-auto text-center"
        >
          <h1>
            Face à l’épidémie de
            <span class="italic font-bold">Covid-19</span> le Gouvernement
            appelle à une mobilisation de toutes les bonnes volontés.
          </h1>
        </div>
        <div class="flex flex-col lg:flex-row items-center justify-center">
          <router-link
            to="/register/responsable"
            class="btn-primary m-3 w-full"
            style="max-width:320px;"
          >
            <span class="uppercase font-light text-xxs"
              >Je suis une structure publique ou associative</span
            >
            <span class="text-lg font-bold">J'ai besoin d'aide</span>
          </router-link>
          <router-link
            to="/register/volontaire"
            class="btn-secondary m-3 w-full"
            style="max-width:320px;"
          >
            <span class="uppercase font-light text-xxs"
              >Je suis volontaire</span
            >
            <span class="text-lg font-bold">Je veux aider</span>
          </router-link>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4">
      <div class="py-4 sm:py-8 md:py-16">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="relative mb-4 sm:col-span-2 lg:col-span-1">
            <svg
              class="absolute"
              width="404"
              height="200"
              fill="none"
              viewBox="0 0 404 200"
              style="bottom: -45px; left: -180px;"
            >
              <defs>
                <pattern
                  id="8b1b5f72-e944-4457-af67-0c6d15a99f38"
                  x="0"
                  y="0"
                  width="20"
                  height="20"
                  patternUnits="userSpaceOnUse"
                >
                  <rect
                    x="0"
                    y="0"
                    width="4"
                    height="4"
                    class="text-gray-200"
                    fill="currentColor"
                  ></rect>
                </pattern>
              </defs>
              <rect
                width="404"
                height="784"
                fill="url(#8b1b5f72-e944-4457-af67-0c6d15a99f38)"
              ></rect>
            </svg>
            <div class="relative">
              <h3
                class="text-3xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-4xl"
              >
                Les 5 missions prioritaires de volontariat
              </h3>
              <div class="inline-flex rounded-full shadow-xl mt-6">
                <a
                  href="#"
                  class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-sm leading-6 font-semibold rounded-full text-gray-900 bg-white hover:text-gray-600 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                >
                  Règles de sécurité
                </a>
              </div>
            </div>
          </div>

          <div>
            <div
              class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
            >
              1
            </div>
            <div class="mt-5">
              <h5 class="text-lg leading-6 font-semibold text-gray-900">
                Aide alimentaire
              </h5>
              <p class="mt-2 text-base leading-6 text-gray-500">
                Je récupère et distribue des produits de première nécessité aux
                plus démunis (aliments, hygiène...)
              </p>
            </div>
          </div>

          <div>
            <div
              class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
            >
              2
            </div>
            <div class="mt-5">
              <h5 class="text-lg leading-6 font-semibold text-gray-900">
                Aide d’urgence
              </h5>
              <p class="mt-2 text-base leading-6 text-gray-500">
                Je prépare / aide à préparer des repas pour les personnes en
                difficultés.
              </p>
            </div>
          </div>

          <div>
            <div
              class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
            >
              3
            </div>
            <div class="mt-5">
              <h5 class="text-lg leading-6 font-semibold text-gray-900">
                Garde d’enfants
              </h5>
              <p class="mt-2 text-base leading-6 text-gray-500">
                Je garde des enfants de soignants ou d’une structure de l’Aide
                Sociale à l’Enfance
              </p>
            </div>
          </div>

          <div>
            <div
              class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
            >
              4
            </div>
            <div class="mt-5">
              <h5 class="text-lg leading-6 font-semibold text-gray-900">
                Lien aux personnes fragiles isolées
              </h5>
              <p class="mt-2 text-base leading-6 text-gray-500">
                Je maintiens un lien (téléphone, visio, mail...) avec des
                personnes fragiles isolées (âgées, situation de handicap, etc.)
              </p>
            </div>
          </div>

          <div>
            <div
              class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
            >
              5
            </div>
            <div class="mt-5">
              <h5 class="text-lg leading-6 font-semibold text-gray-900">
                Solidarité de proximité
              </h5>
              <p class="mt-2 text-base leading-6 text-gray-500">
                Je fais les courses de produits essentiels pour mes voisins les
                plus fragiles.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/brainstorming.jpeg"
        />
      </div>
      <div class="lg:w-1/2 p-4 sm:p-8 md:p-16 lg:flex lg:justify-start">
        <div class="lg:max-w-xl">
          <h2 class="titre-1">
            Votre structure est engagée dans la crise sanitaire face au Covid
            19&nbsp;?
          </h2>
          <p class="py-3 text-gray-600 text-lg">
            Identifiez votre besoin et partagez-le sur la Réserve Civique pour
            que des citoyens vous viennent en aide dans le respect des règles de
            sécurité et des directives du gouvernement.
          </p>
          <div class="mt-3">
            <router-link to="/register/responsable" class="btn-primary"
              >Proposer des missions</router-link
            >
          </div>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="lg:w-1/2 p-4 sm:p-8 md:p-16 lg:flex lg:justify-end">
        <div class="lg:max-w-xl">
          <h2 class="titre-1">
            Vous souhaitez rejoindre les volontaires&nbsp;?
          </h2>
          <p class="py-3 text-gray-600 text-lg">
            Aidez un travailleur en première ligne vivant proche de chez vous en
            gardant un enfant ou en faisant des courses, et engagez-vous dans la
            lutte contre l’épidémie.
          </p>
          <div class="py-3">
            <router-link to="/missions" class="btn-secondary"
              >Trouver une mission</router-link
            >
          </div>
          <div class="pt-3">
            <router-link
              to="/regles-de-securite"
              class="text-15 text-gray-600 hover:underline"
              >Cet engagement nécessite un respect strict des règles sanitaires
              applicables&nbsp;›</router-link
            >
          </div>
        </div>
      </div>
      <div class="lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/volontaires.jpeg"
        />
      </div>
    </div>

    <!-- <div class="bg-gray-100">
      <div class="container mx-auto px-4">
        <div class="pt-8 md:pt-12 lg:pt-24 text-center">
          <h2 class="titre-1">Les partenaires de la Réserve Civique</h2>
          <div class="text-gray-600 text-xl mb-12">
            Collectivités, plateformes de l'engagement et spécialistes du
            bénévolat, rejoignez le mouvement !
          </div>
        </div>
        <hr class="text-gray-600 py-4 w-24 border-t-4 mx-auto" />
        <div class="flex flex-col lg:flex-row flex-wrap pb-8 md:pb-12">
          <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
            <div class="text-gray-800 text-sm uppercase font-bold mb-2">
              WEBASSOC
            </div>
            <div class="text-gray-600 text-lg">
              Aide aux associations à se renforcer avec internet
            </div>
          </div>
          <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
            <div class="text-gray-800 text-sm uppercase font-bold mb-2">
              Tous Bénévoles
            </div>
            <div class="text-gray-600 text-lg">
              Mise en relation des associations et des candidats bénévoles
            </div>
          </div>
          <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
            <div class="text-gray-800 text-sm uppercase font-bold mb-2">
              France Bénévolat
            </div>
            <div class="text-gray-600 text-lg">
              Promouvoir, valoriser et favoriser le bénévolat
            </div>
          </div>
          <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
            <div class="text-gray-800 text-sm uppercase font-bold mb-2">
              Vendredi
            </div>
            <div class="text-gray-600 text-lg">
              Des emplois partagés entre entreprise et association
            </div>
          </div>
          <div class="w-full lg:max-w-1/3 p-3 my-3 text-center">
            <div class="text-gray-800 text-sm uppercase font-bold mb-2">
              En première ligne
            </div>
            <div class="text-gray-600 text-lg">
              Le site pour aider les personnes qui luttent contre le coronavirus
            </div>
          </div>
          <div
            class="w-full lg:max-w-1/3 p-3 text-center flex justify-center items-center"
          >
            <a href="#" class="btn-primary">Rejoindre les partenaires</a>
          </div>
        </div>
      </div>
    </div> -->

    <div class="lg:flex">
      <div
        class="bg-blue-700 text-white lg:w-1/2 px-4 py-8 md:px-8 md:py-12 lg:px-12 lg:py-16"
      >
        <div
          class="lg:max-w-xl flex flex-col items-center text-center lg:items-start lg:text-left lg:ml-auto"
        >
          <h2 class="titre-1 text-white">Informations Coronavirus</h2>
          <p class="py-3 text-lg">
            Toutes les réponses aux questions que vous vous posez sur le
            Coronavirus COVID-19
          </p>
          <div class="mt-3">
            <a
              href="https://www.gouvernement.fr/info-coronavirus"
              target="_blank"
              class="btn-secondary plain"
              >S'informer</a
            >
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

<style lang="sass" scoped>
@import url('https://fonts.googleapis.com/css?family=Montserrat:300,400,700&display=swap')
</style>
