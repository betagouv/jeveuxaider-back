<template>
  <div class>
    <div
      style="background-image: url(/images/bg_header_home.jpg); background-size: cover; background-repeat: no-repeat;"
      class="pt-1 pb-12 md:py-12"
    >
      <AppHeader background="bg-transparent" />
      <div class="container mx-auto px-4">
        <div
          class="py-16 text-2xl lg:text-5xl text-white max-w-5xl mx-auto text-center leading-tight"
        >
          <h1 class="hide-br">
            Face à l’épidémie de
            <span class="italic font-bold">Covid-19</span>
            <br>le Gouvernement appelle à la mobilisation
            <br>générale des
            solidarités.
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
            <span class="text-lg font-bold">Proposer une mission</span>
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
      <div class="py-8 md:py-16 max-w-4xl mx-auto">
        <div class="text-md sm:text-md lg:text-xl text-gray-900 text-center italic">
          «&nbsp;Jamais de telles épreuves ne se surmontent en solitaire. C’est
          au contraire en solidaires, en disant «&nbsp;nous&nbsp;» plutôt qu’en
          pensant «&nbsp;je&nbsp;» que nous relèverons cet immense défi.&nbsp;»
        </div>
        <div
          class="text-md text-center text-gray-900"
        >
          Emmanuel Macron, Président de la République, 12 mars 2020
        </div>
        <div class="mt-8 text-lg sm:text-xxl lg:text-2xl text-gray-900">
          Cet espace d’engagement est ouvert à tous. Que l’on soit bénévole dans
          l’âme, habitué depuis toujours des missions associatives. Ou que l’on
          ait à cœur, pour la première fois, de donner un peu de temps et
          d’énergie. Parce que cette guerre sanitaire est sans précédent, parce
          qu’elle nous concerne toutes et tous et parce que nous voulons la
          gagner. Ensemble.
        </div>
        <div class="text-2xl font-bold">
          #jeveuxaider
        </div>
      </div>
    </div>

    <div class="bg-gray-50 py-8 md:py-16 overflow-hidden">
      <div class="container mx-auto px-4">
        <div class="relative lg:grid lg:grid-cols-3 lg:col-gap-8">
          <div class="lg:col-span-1">
            <h3
              class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl leading-tight hide-br"
            >
              Les 8 missions
              <br>prioritaires
            </h3>
          </div>

          <svg
            class="hidden lg:block absolute"
            width="404"
            height="300"
            fill="none"
            viewBox="0 0 404 300"
            style="top: 150px; left: -152px;"
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
                  fill="#e6e7eb"
                />
              </pattern>
            </defs>
            <rect
              width="404"
              height="300"
              fill="url(#8b1b5f72-e944-4457-af67-0c6d15a99f38)"
            />
          </svg>

          <div
            class="mt-10 sm:grid sm:grid-cols-2 sm:col-gap-8 sm:row-gap-10 lg:col-span-2 lg:mt-0"
          >
            <div>
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >
                1
              </div>
              <div class="mt-5">
                <h5
                  class="text-lg leading-6 font-semibold text-gray-900"
                >
                  Aide alimentaire et d’urgence.
                </h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  Je distribue des produits de première nécessité (aliments,
                  hygiène…) et des repas aux plus démunis.
                </p>
              </div>
            </div>
            <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >
                2
              </div>
              <div class="mt-5">
                <h5
                  class="text-lg leading-6 font-semibold text-gray-900"
                >
                  Garde exceptionnelle d’enfants.
                </h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  J’aide à garder des enfants de soignants ou d’une structure de
                  l’Aide Sociale à l’Enfance.
                </p>
              </div>
            </div>

            <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >
                3
              </div>
              <div class="mt-5">
                <h5
                  class="text-lg leading-6 font-semibold text-gray-900"
                >
                  Lien avec les personnes fragiles isolées.
                </h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  Je participe à maintenir le lien téléphone, visio, mail...
                  avec des personnes fragiles isolées.
                </p>
              </div>
            </div>
            <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >
                4
              </div>
              <div class="mt-5">
                <h5 class="text-lg leading-6 font-semibold text-gray-900">
                  Solidarité de proximité.
                </h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  Je fais les courses de produits essentiels pour mes voisins
                  les plus fragiles.
                </p>
              </div>
            </div>
            <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >
                5
              </div>
              <div class="mt-5">
                <h5 class="text-lg leading-6 font-semibold text-gray-900">
                  Soutien scolaire.
                </h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  J’aide à distance les élèves à faire leurs devoirs.
                </p>
              </div>
            </div>
            <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >
                6
              </div>
              <div class="mt-5">
                <h5 class="text-lg leading-6 font-semibold text-gray-900">
                  Fabrication et distribution d’équipements de protection grand public.
                </h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  Je participe à la confection d’équipements de protection grand public ou à leur distribution.
                </p>
              </div>
            </div>
            <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >
                7
              </div>
              <div class="mt-5">
                <h5 class="text-lg leading-6 font-semibold text-gray-900">
                  Soutien à la mobilisation sanitaire.
                </h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  J’aide les personnels des établissements de santé ou participe à une action à but sanitaire.
                </p>
              </div>
            </div>
            <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >
                8
              </div>
              <div class="mt-5">
                <h5 class="text-lg leading-6 font-semibold text-gray-900">
                  Soutien à la reprise des missions de service public.
                </h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  Je contribue à la reprise des missions de service public en lien avec la population.
                </p>
              </div>
            </div>
            <!--        <div class="mt-10 sm:mt-0">
              <div
                class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
              >6</div>
              <div class="mt-5">
                <h5 class="text-lg leading-6 font-semibold text-gray-900">Soutien aux personnes âgées en établissement.</h5>
                <p class="mt-2 text-base leading-6 text-gray-500">
                  J’aide les personnels des EHPAD sur des tâches du quotidien et/ou les résidents à maintenir un lien avec leurs proches grâce aux outils numériques.
                </p>
              </div>
            </div>
            -->
          </div>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="hidden lg:block lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/structure.jpg"
        >
      </div>
      <div class="border-t lg:border-t-0 lg:w-1/2 py-8 md:py-16 lg:p-16 lg:flex lg:justify-start">
        <div class="container mx-auto px-4 lg:mx-0 lg:px-0 lg:max-w-xl">
          <h2 class="titre-1">
            Face à la crise sanitaire du Covid 19, votre structure a besoin de
            renforts&nbsp;?
          </h2>
          <p class="py-3 text-gray-600 text-lg">
            Publiez une offre de mission pour que des citoyens viennent vous
            prêter main forte, dans le respect des règles de sécurité.
          </p>
          <div class="mt-3">
            <router-link
              to="/register/responsable"
              class="btn-primary"
            >
              Proposer des missions
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="border-t lg:border-t-0 lg:w-1/2 py-8 md:py-16 lg:p-16 lg:flex lg:justify-start">
        <div class="container mx-auto px-4 lg:mx-0 lg:px-0 lg:max-w-xl">
          <h2 class="titre-1">
            Vous souhaitez vous engager au plus près de chez vous&nbsp;?
          </h2>
          <p class="py-3 text-gray-600 text-lg">
            Vous pouvez également choisir de contribuer à la lutte contre
            l’épidémie en menant des actions de solidarité dans votre immeuble
            ou dans votre rue.
          </p>
          <div class="py-3">
            <a
              href="/files/Kit-Coronavirus-1.pdf"
              target="_blank"
              class="btn-secondary"
              onclick="return tag.click.send({elem:this, name:'Kit_de_solidarite', level2:'0', type:'download'});"
            >Télécharger le kit de solidarité de proximité</a>
          </div>
        </div>
      </div>
      <div class="hidden lg:block lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/structure-2.jpg"
        >
      </div>
    </div>

    <div class="lg:flex">
      <div class="hidden lg:block lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/structure-3.jpg"
        >
      </div>
      <div class="border-t lg:border-t-0 lg:w-1/2 py-8 md:py-16 lg:p-16 lg:flex lg:justify-start">
        <div class="container mx-auto px-4 lg:mx-0 lg:px-0 lg:max-w-xl">
          <h2 class="titre-1">
            Vous souhaitez rejoindre une structure qui a besoin d'aide&nbsp;?
          </h2>
          <p class="py-3 text-gray-600 text-lg">
            Aidez un soignant en première ligne vivant proche de chez vous en
            gardant son enfant, distribuez des produits alimentaires aux plus
            démunis, gardez le lien avec une personne isolée...
          </p>
          <div class="mt-3">
            <router-link
              to="/missions"
              class="btn-secondary"
            >
              Trouver une mission
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="py-8 lg:py-20 bg-gray-50">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h3
            class="text-3xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-4xl"
          >
            Les 5 règles de sécurité des volontaires
          </h3>
        </div>

        <div class="mt-20">
          <ul class="md:grid md:grid-cols-2 md:col-gap-8 md:row-gap-10">
            <li>
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                  >
                    1
                  </div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg text-gray-900">
                    En toutes circonstances,
                    <b>je fais en sorte de garder ma distance de sécurité</b>
                    d’au moins un mètre avec les bénéficiaires et les autres
                    bénévoles.
                  </h5>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                  >
                    2
                  </div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg text-gray-900">
                    En toutes circonstances,
                    <b>j’applique les gestes barrières</b> pour me protéger et
                    protéger les bénéficiaires et les autres bénévoles.
                  </h5>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center h-12 w-12 font-semibold rounded-md bg-blue-800 text-white"
                  >
                    3
                  </div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg text-gray-900">
                    <b>Je suis un ambassadeur des règles de sécurité</b> et des
                    gestes barrière que j’explique et promeus auprès des
                    bénéficiaires et des autres bénévoles.
                  </h5>
                </div>
              </div>
            </li>
            <!-- <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                  >
                    4
                  </div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg text-gray-900">
                    Pour accomplir ma mission bénévole,
                    <b>je n’utilise pas les transports en commun</b> afin de
                    limiter les risques de contamination.
                  </h5>
                </div>
              </div>
            </li>-->
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center h-12 w-12 font-semibold rounded-md bg-blue-800 text-white"
                  >
                    4
                  </div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg text-gray-900">
                    <b>Au moindre doute d’infection</b>, sans attendre,
                    j’informe l’association et je reste chez moi.
                  </h5>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                  >
                    5
                  </div>
                </div>
                <div class="ml-4">
                  <h5 class="text-lg text-gray-900">
                    <b>Âgé de 70 ans ou plus ou porteur d’une maladie chronique</b>, je ne m’engage que dans des missions à distance, depuis
                    mon domicile, pour protéger au maximum ma santé et celle des
                    autres.
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

    <div class="p-8 lg:p-20">
      <div class="relative max-w-4xl mx-auto">
        <div class="lg:text-center">
          <h2 class="text-3xl leading-9 tracking-tight font-extrabold text-gray-900 sm:text-4xl sm:leading-10">
            S'informer c'est s'engager
          </h2>
          <p class="mt-3 max-w-2xl mx-auto text-xl leading-7 text-gray-500 sm:mt-4">
            Restez informé et soutenez l'emploi des secteurs prioritaires
          </p>
        </div>
        <div class="mt-12 grid gap-12 max-w-lg lg:max-w-4xl mx-auto lg:grid-cols-2 lg:max-w-none">
          <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <a
              href="https://www.gouvernement.fr/info-coronavirus"
              target="_blank"
            >
              <div class="flex-shrink-0">
                <img
                  class=" w-full object-cover"
                  src="images/rcv.jpg"
                  alt=""
                >
              </div>
              <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                <div class="flex-1">
                  <p class="text-md leading-5 font-medium text-blue-800">
                    #RestezChezVous
                  </p>
                  <h3 class="mt-2 text-xl leading-7 font-semibold text-gray-900">
                    Informations Coronavirus
                  </h3>
                  <p class="mt-3 text-base leading-6 text-gray-500">
                    Tenez-vous informé des informations officielles sur le COVID-19 grâce au point de situation du gouvernement.
                  </p>
                </div>
              </div>
            </a>
          </div>
          <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <a
              href="https://mobilisationemploi.gouv.fr/"
              target="_blank"
            >
              <div class="flex-shrink-0">
                <img
                  class="w-full object-cover"
                  src="images/pole.jpg"
                  alt=""
                >
              </div>
              <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                <div class="flex-1">
                  <p class="text-md leading-5 font-medium text-blue-800">
                    #TousMobilisés
                  </p>
                  <h3 class="mt-2 text-xl leading-7 font-semibold text-gray-900">
                    Mobilisez-vous pour l'emploi
                  </h3>
                  <p class="mt-3 text-base leading-6 text-gray-500">
                    Actuellement en recherche d’emploi ou en activité partielle, soutenez et venez travailler avec les salarié(e)s des secteurs prioritaires.
                  </p>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<style lang="sass" scoped>
.hide-br
  br
    @apply hidden
      @screen lg
        @apply inline
</style>
