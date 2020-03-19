<template>
  <div class="font-montserrat">
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
              to="/login"
              class="mx-auto text-white text-sm font-thin hover:underline"
            >Se connecter</router-link>
          </div>
        </div>
        <div class="py-16 text-2xl lg:text-5xl text-white max-w-5xl mx-auto text-center">
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
            <span
              class="uppercase font-light text-xxs"
            >Je suis une structure publique ou associative</span>
            <span class="text-lg font-bold">J'ai besoin d'aide</span>
          </router-link>
          <router-link
            to="/register/volontaire"
            class="btn-secondary m-3 w-full"
            style="max-width:320px;"
          >
            <span class="uppercase font-light text-xxs">Je suis volontaire</span>
            <span class="text-lg font-bold">Je veux aider</span>
          </router-link>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4">
      <div class="py-4 sm:py-8 md:py-16 max-w-4xl mx-auto">
        <div class="text-3xl text-gray-900">
          Cuisiner un repas et le livrer. Ecouter et parler. Donner et partager. Il y a autant de façon de s’engager.
          Que l’on soit bénévole dans l’âme, habitué depuis toujours des missions associatives. Ou que l’on ait à cœur, pour la première fois, de donner un peu de temps et d’énergie.
          Parce que cette guerre sanitaire est sans précédent, parce qu’elle nous concerne toutes et tous et parce que nous voulons la gagner. Ensemble.
        </div>
        <div class="text-3xl font-bold">#jeveuxaider</div>
      </div>
    </div>

    <div class="bg-gray-50 py-4 sm:py-8 md:py-16">
      <div class="container mx-auto px-4">
        <div class="relative lg:grid lg:grid-cols-3 lg:col-gap-8">
          <div class="lg:col-span-1">
            <h3
              class="text-3xl leading-14 font-extrabold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
            >
              Les 4 missions
              <br />vitales
            </h3>
          </div>
          <div
            class="mt-10 sm:grid sm:grid-cols-2 sm:col-gap-8 sm:row-gap-10 lg:col-span-2 lg:mt-0"
          >
            <div>
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >1</div>
              <div class="mt-5">
                <h5
                  class="text-lg leading-6 font-semibold text-gray-900"
                >Aide alimentaire et d’urgence.</h5>
                <p
                  class="mt-2 text-base leading-6 text-gray-500"
                >Je distribue des produits de première nécessité (aliments, hygiène…) et des repas aux plus démunis</p>
              </div>
            </div>
            <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >2</div>
              <div class="mt-5">
                <h5
                  class="text-lg leading-6 font-semibold text-gray-900"
                >Garde exceptionnelle d’enfants.</h5>
                <p
                  class="mt-2 text-base leading-6 text-gray-500"
                >J’aide à garder des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance.</p>
              </div>
            </div>

            <div
              class="mt-10 sm:grid sm:grid-cols-2 sm:col-gap-8 sm:row-gap-10 lg:col-span-2 lg:mt-0"
            >
              <div>
                <div
                  class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                >3</div>
                <div class="mt-5">
                  <h5
                    class="text-lg leading-6 font-semibold text-gray-900"
                  >Lien avec les personnes fragiles isolées.</h5>
                  <p
                    class="mt-2 text-base leading-6 text-gray-500"
                  >Je participe à maintenir le lien (téléphone, visio, mail...) avec des personnes fragiles isolées : personnes âgées, malades ou en situation de handicap.</p>
                </div>
              </div>
              <div class="mt-10 sm:mt-0">
                <div
                  class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                >4</div>
                <div class="mt-5">
                  <h5 class="text-lg leading-6 font-semibold text-gray-900">Solidarité de proximité.</h5>
                  <p
                    class="mt-2 text-base leading-6 text-gray-500"
                  >Je fais les courses de produits essentiels pour mes voisins les plus fragiles.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/structure.jpg"
        />
      </div>
      <div class="lg:w-1/2 p-4 sm:p-8 md:p-16 lg:flex lg:justify-start">
        <div class="lg:max-w-xl">
          <h2 class="titre-1">
            Votre structure est engagée dans la crise sanitaire face au Covid
            19 et vous avez besoin de volontaires&nbsp;?
          </h2>
          <p
            class="py-3 text-gray-600 text-lg"
          >Publiez une offre de mission pour que des citoyens viennent vous prêter main forte, dans le respect des règles de sécurité.</p>
          <div class="mt-3">
            <router-link to="/register/responsable" class="btn-primary">Proposer des missions</router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="lg:w-1/2 p-4 sm:p-8 md:p-16 lg:flex lg:justify-end">
        <div class="lg:max-w-xl">
          <h2 class="titre-1">Vous souhaitez vous engager au plus près de chez vous&nbsp;?</h2>
          <p
            class="py-3 text-gray-600 text-lg"
          >Vous pouvez également choisir de contribuer à la lutte contre l’épidémie en menant des actions de solidarité dans votre immeuble ou dans votre rue.</p>
          <div class="py-3">
            <router-link to="/missions" class="btn-secondary">Trouver une mission</router-link>
          </div>
          <div class="pt-3">
            <a
              href="/#"
              class="text-15 text-gray-600 hover:underline"
            >Télécharger le kit de solidarité de proximité&nbsp;›</a>
          </div>
        </div>
      </div>
      <div class="lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/structure-2.jpg"
        />
      </div>
    </div>

    <div class="lg:flex">
      <div class="lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/structure-3.jpg"
        />
      </div>
      <div class="lg:w-1/2 p-4 sm:p-8 md:p-16 lg:flex lg:justify-start">
        <div class="lg:max-w-xl">
          <h2 class="titre-1">Vous souhaitez rejoindre une structure qui a besoin d'aide&nbsp;?</h2>
          <p
            class="py-3 text-gray-600 text-lg"
          >Vous pouvez également choisir de contribuer à la lutte contre l’épidémie en menant des actions de solidarité dans votre immeuble ou dans votre rue.</p>
          <div class="mt-3">
            <router-link to="/missions" class="btn-secondary">Trouver une mission</router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="py-20 bg-gray-50">
      <div class="container mx-auto px-6 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h3
            class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
          >Les 6 règles de sécurité des volontaires</h3>
        </div>

        <div class="mt-20">
          <ul class="md:grid md:grid-cols-2 md:col-gap-8 md:row-gap-10">
            <li>
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                  >1</div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg leading-6 font-medium text-gray-900">
                    En toutes circonstances,
                    <b>je fais en sorte de garder ma distance de sécurité</b> d’au moins un mètre avec les bénéficiaires et les autres bénévoles.
                  </h5>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                  >2</div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg leading-6 font-medium text-gray-900">
                    En toutes circonstances,
                    <b>j’applique les gestes barrières</b> pour me protéger et protéger les bénéficiaires et les autres bénévoles.
                  </h5>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center h-12 w-12 font-semibold rounded-md bg-blue-800 text-white"
                  >3</div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg leading-6 font-medium text-gray-900">
                    <b>Je suis un ambassadeur des règles de sécurité</b> et des gestes barrière que j’explique et promeus auprès des bénéficiaires et des autres bénévoles.
                  </h5>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                  >4</div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg leading-6 font-medium text-gray-900">
                    Pour accomplir ma mission bénévole,
                    <b>je n’utilise pas les transports en commun</b> afin de limiter les risques de contamination.
                  </h5>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center h-12 w-12 font-semibold rounded-md bg-blue-800 text-white"
                  >5</div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg leading-6 font-medium text-gray-900">
                    <b>Au moindre doute d’infection</b>, sans attendre, j’informe l’association et je reste chez moi.
                  </h5>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                  >6</div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg leading-6 font-medium text-gray-900">
                    <b>Âgé de 70 ans ou plus ou porteur d’une maladie chronique</b>, je ne m’engage que dans des missions à distance, depuis mon domicile, pour protéger au maximum ma santé et celle des autres.
                  </h5>
                </div>
              </div>
            </li>
          </ul>
        </div>
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
    </div>-->

    <!-- <div class="lg:flex">
      <div class="bg-blue-700 text-white lg:w-1/2 px-4 py-8 md:px-8 md:py-12 lg:px-12 lg:py-16">
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
    </div>-->

    <AppFooter />
  </div>
</template>

<style lang="sass" scoped>
@import url('https://fonts.googleapis.com/css?family=Montserrat:300,400,700&display=swap')
</style>
