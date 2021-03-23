<template>
  <div>
    <div class="relative bg-blue-800 overflow-hidden z-10">
      <div
        class="absolute bottom-0 bg-gradient-to-b from-transparent to-blue-800 z-10 h-32 w-full"
      ></div>

      <img
        class="z-1 object-cover absolute h-screen lg:h-auto"
        alt="Je Veux Aider"
        :srcSet="bgHeroMultipleSizes.srcSet"
        :src="bgHeroMultipleSizes.src"
        width="100%"
        height="100%"
      />

      <div
        class="p-6 lg:pt-40 lg:pb-40 mt-12 relative w-full lg:inset-y-0 text-center z-10"
      >
        <client-only placeholder="Je veux aider, bénévolat partout en France">
          <vue-typed-js
            :strings="strings"
            :loop="true"
            :start-delay="70"
            :type-speed="70"
            :back-delay="2000"
            cursor-char="|"
          >
            <div
              class="mx-auto text-4xl lg:text-5xl tracking-tight leading-10 font-bold text-white h-40 md:h-auto"
            >
              Je veux <br class="lg:hidden" /><span class="typing"></span>
            </div>
          </vue-typed-js>
        </client-only>

        <div
          class="mt-3 max-w-md mx-auto text-white text-xl lg:text-2xl md:mt-2 md:max-w-3xl"
        >
          <h1>Devenez bénévole et trouvez des missions en quelques clics</h1>
          <br class="hidden lg:block" />
          <b>près de chez vous</b> ou <b>à distance</b>
        </div>

        <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-5">
          <div class="rounded-full shadow-lg">
            <div
              class="font-bold cursor-pointer flex items-center shadow-lg justify-center border border-transparent text-2xl leading-6 rounded-full text-white bg-green-400 py-4 px-10 pb-5 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
              @click="$store.commit('toggleSearchOverlay')"
            >
              Je veux aider
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-blue-800 pt-6">
      <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <h2
            class="text-3xl leading-9 font-bold tracking-tight text-white sm:text-5xl sm:leading-10"
          >
            Plus de 330 000 bénévoles
          </h2>
          <p class="mt-3 text-xl lg:text-2xl leading-7 text-blue-200 sm:mt-4">
            soutiennent des milliers d'associations et d'organisations publiques
          </p>
        </div>
      </div>
      <div class="mt-10 pb-12 bg-gray-50">
        <div class="relative">
          <div class="absolute inset-0 h-14 bg-blue-800"></div>
          <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
              <div
                class="rounded-lg bg-white py-1 shadow-lg grid grid-cols-2 lg:grid-cols-5"
              >
                <div
                  class="flex lg:flex-col border-b border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r justify-center"
                >
                  <img
                    class="m-auto"
                    alt="partenaire engagement J'agis pour la nature"
                    src="@/assets/images/logo-jagis-pour-la-nature.png"
                    width="100px"
                    height="40px"
                  />
                </div>
                <div
                  class="flex lg:flex-col border-b border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r justify-center"
                >
                  <img
                    class="m-auto"
                    alt="partenaire engagement Banque Alimentaire"
                    src="@/assets/images/logo_banquealimentaire.png"
                    width="150px"
                    height="60px"
                  />
                </div>
                <div
                  class="flex flex-col border-b border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r justify-center"
                >
                  <img
                    class="m-auto"
                    src="@/assets/images/logo_emmaus.png"
                    alt="partenaire engagement Emmaus"
                    width="150px"
                    height="60px"
                  />
                </div>
                <div
                  class="flex flex-col border-b border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r justify-center"
                >
                  <img
                    class="m-auto"
                    src="@/assets/images/logo_aphp.png"
                    alt="partenaire engagement APHP"
                    width="150px"
                    height="60px"
                  />
                </div>
                <a
                  href="https://www.jeveuxaider.gouv.fr/engagement/organisations/"
                  target="_blank"
                  rel="noopener"
                  class="flex flex-col border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r uppercase leading-6 text-xs text-gray-800 col-span-2 lg:col-span-1"
                >
                  parmi Plus de
                  <span class="text-4xl font-bold">5000</span>
                  organisations
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-gray-50 pt-6 shadow-sm z-10 relative">
      <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-auto text-center">
          <span
            class="text-2xl lg:text-3xl leading-8 lg:leading-10 block lg:inline-flex font-bold tracking-tight text-gray-800"
          >
            <span class="block lg:inline-flex">
              Trouvez une mission de bénévolat</span
            >

            <nuxt-link
              to="/missions-benevolat?refinementList[type][0]=Mission en présentiel"
              class="tracking-normal lg:inline-flex mx-4 mt-3 lg:-mt-2 text-gray-800 font-semibold bg-white border border-transparent rounded-full shadow-lg py-4 pb-5 px-5 inline-flex items-center text-2xl lg:text-3xl leading-6 hover:scale-105 hover:text-blue-800 transform transition duration-150 ease-in-out"
            >
              près de chez vous</nuxt-link
            >
            <span class="block lg:inline-flex my-4 lg:my-0">ou</span>

            <nuxt-link
              to="/missions-benevolat?refinementList[type][0]=Mission à distance"
              class="tracking-normal lg:ml-4 lg:inline-flex lg:-mt-2 text-gray-800 font-semibold bg-white border border-transparent rounded-full shadow-lg py-4 pb-5 px-5 inline-flex items-center text-2xl lg:text-3xl leading-6 hover:scale-105 hover:text-blue-800 transform transition duration-150 ease-in-out"
            >
              à distance</nuxt-link
            >
          </span>
        </div>
      </div>
    </div>

    <div class="bg-gray-50 pb-20 px-4 md:px-6 lg:px-8">
      <p
        class="text-center pt-12 pb-2 text-base leading-6 font-semibold uppercase text-gray-500 tracking-wider"
      >
        Parmi les missions prioritaires
      </p>
      <div
        class="mt-10 grid gap-5 max-w-6xl mx-auto lg:grid-cols-3 lg:max-w-none px-4 lg:px-0"
      >
        <nuxt-link
          v-for="mission in missions_prioritaires"
          :key="mission.name"
          :to="mission.link"
          class="flex max-w-lg mx-auto flex-col rounded-lg shadow-lg overflow-hidden hover:scale-105 transform transition duration-150 ease-in-out"
        >
          <div class="flex-shrink-0">
            <div class="absolute transform translate-y-px ml-6 mt-5">
              <div class="flex justify-center transform">
                <span
                  class="inline-flex shadow-lg text-xs rounded-full bg-pink-100 px-3 py-1 leading-5 font-semibold tracking-wider uppercase text-pink-700"
                >
                  Mission prioritaire
                </span>
              </div>
            </div>
            <div>
              <img
                class="h-48 w-full object-cover"
                :src="mission.image"
                :alt="mission.name"
                width="600px"
                height="286px"
              />
            </div>
          </div>
          <div class="flex-1 bg-white p-8 pt-4 flex flex-col justify-between">
            <div class="flex-1">
              <div
                class="text-base leading-10 font-semibold tracking-wide uppercase text-gray-500 hover:text-blue-800"
              >
                {{ mission.thematique }}
              </div>
              <h3 class="text-xl leading-7 font-bold text-gray-900">
                {{ mission.name }}
              </h3>
            </div>
            <div class="mt-4 flex items-center">
              <div class="flex-shrink-0">
                <div
                  class="hidden sm:block flex-shrink-0 rounded-md p-2 text-center bg-blue-800"
                >
                  <img
                    :src="mission.thematique_image"
                    :alt="mission.thematique"
                    style="width: 28px"
                  />
                </div>
              </div>
              <div class="ml-4">
                <p
                  class="text-sm leading-5 tracking-tight font-medium text-gray-800"
                  v-html="mission.nb_missions_text"
                ></p>
              </div>
            </div>
          </div>
        </nuxt-link>
      </div>
    </div>

    <div
      class="relative bg-gray-100 pt-16 pb-10 px-4 sm:px-6 lg:px-8 shadow-lg z-10"
    >
      <div class="relative max-w-6xl mx-auto">
        <div class="text-center">
          <h2
            class="text-3xl leading-9 tracking-tight font-bold text-gray-800 sm:text-5xl sm:leading-10"
          >
            Trouvez votre domaine d'action
          </h2>
        </div>

        <div
          class="mt-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2 max-w-5xl mx-auto"
        >
          <div
            class="bg-red-500 overflow-hidden shadow-lg rounded-lg hover:scale-105 transform transition duration-150 ease-in-out"
          >
            <div class="bg-white px-4 py-2 sm:px-6">
              <div class="text-sm leading-5">
                <nuxt-link
                  to="/domaines-action/solidarite-et-insertion"
                  class="text-base leading-10 font-semibold tracking-wide uppercase text-gray-800"
                >
                  solidarité et insertion</nuxt-link
                >
              </div>
            </div>

            <div class="px-4 py-5 sm:p-8">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-white shadow-lg rounded-lg p-3">
                  <div
                    class="opacity-50 svg-100"
                    style="width: 36px"
                    v-html="
                      require('@/assets/images/dynamic/vivre-ensemble.svg?include')
                    "
                  />
                </div>
                <div class="ml-4 w-0 flex-1">
                  <nuxt-link
                    to="/domaines-action/solidarite-et-insertion"
                    class="block"
                  >
                    <h3 class="text-xl leading-6 font-semibold text-white">
                      Venez en aide aux plus démunis et aux personnes isolées
                    </h3>
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>

          <div
            class="bg-green-500 overflow-hidden shadow-lg rounded-lg hover:scale-105 transform transition duration-150 ease-in-out"
          >
            <div class="bg-gray-50 px-4 py-2 sm:px-6">
              <div class="text-sm leading-5">
                <nuxt-link
                  to="/domaines-action/protection-de-la-nature"
                  class="text-base leading-10 font-semibold tracking-wide uppercase text-gray-800"
                >
                  protection de la nature</nuxt-link
                >
              </div>
            </div>
            <div class="px-4 py-5 sm:p-8">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-white shadow-lg rounded-lg p-3">
                  <div
                    class="opacity-50 svg-100"
                    style="width: 36px"
                    v-html="
                      require('@/assets/images/dynamic/environnement.svg?include')
                    "
                  />
                </div>
                <div class="ml-4 w-0 flex-1">
                  <nuxt-link
                    to="/domaines-action/protection-de-la-nature"
                    class="block"
                  >
                    <h3 class="text-xl leading-6 font-semibold text-white">
                      Participez à des activités en pleine nature pour protéger
                      l'environnement
                    </h3>
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>
          <div
            class="bg-blue-800 overflow-hidden shadow-lg rounded-lg hover:scale-105 transform transition duration-150 ease-in-out"
          >
            <div class="bg-gray-50 px-4 py-2 sm:px-6">
              <div class="text-sm leading-5">
                <nuxt-link
                  to="/domaines-action/education-pour-tous"
                  class="text-base leading-10 font-semibold tracking-wide uppercase text-gray-800"
                >
                  éducation pour tous</nuxt-link
                >
              </div>
            </div>
            <div class="px-4 py-5 sm:p-8">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-white shadow-lg rounded-lg p-3">
                  <div
                    class="opacity-50 svg-100"
                    style="width: 36px"
                    v-html="
                      require('@/assets/images/dynamic/education.svg?include')
                    "
                  />
                </div>
                <div class="ml-4 w-0 flex-1">
                  <nuxt-link
                    to="/domaines-action/education-pour-tous"
                    class="block"
                  >
                    <h3 class="text-xl leading-6 font-semibold text-white">
                      Soutenez les parcours scolaires et professionnels des
                      jeunes
                    </h3>
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>
          <div
            class="bg-purple-800 overflow-hidden shadow-lg rounded-lg hover:scale-105 transform transition duration-150 ease-in-out"
          >
            <div class="bg-gray-50 px-4 py-2 sm:px-6">
              <div class="text-sm leading-5">
                <nuxt-link
                  to="/domaines-action/sante-pour-tous"
                  class="text-base leading-10 font-semibold tracking-wide uppercase text-gray-800"
                >
                  santé pour tous</nuxt-link
                >
              </div>
            </div>
            <div class="px-4 py-5 sm:p-8">
              <div class="flex items-center">
                <div class="flex-shrink-0 bg-white shadow-lg rounded-lg p-3">
                  <div
                    class="opacity-50 svg-100"
                    style="width: 36px"
                    v-html="
                      require('@/assets/images/dynamic/sante.svg?include')
                    "
                  />
                </div>
                <div class="ml-4 w-0 flex-1">
                  <nuxt-link
                    to="/domaines-action/sante-pour-tous"
                    class="block"
                  >
                    <h3 class="text-xl leading-6 font-semibold text-white">
                      Venez en aide aux personnes malades, à leurs proches ou
                      aux soignants
                    </h3>
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="relative max-w-screen-xl text-center mx-auto">
        <div class="max-w-md mx-auto lg:max-w-screen-xl">
          <p
            class="text-center pt-10 text-base leading-6 font-semibold uppercase text-gray-500 tracking-wider"
          >
            les autres domaines d'action
          </p>
          <div class="px-0 lg:px-2 py-8 lg:flex lg:items-center">
            <div class="flex-1">
              <nuxt-link
                v-for="domaine in domaines"
                :key="domaine.name"
                :to="domaine.link"
                class="mx-0 my-2 lg:m-2 inline-flex px-3 py-4 rounded-full text-base leading-8 font-semibold tracking-wide uppercase bg-white shadow-lg text-gray-800 hover:scale-105 transform transition duration-150 ease-in-out hover:text-blue-800"
              >
                <img
                  :alt="domaine.name"
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  :src="require(`@/assets/images/dynamic/${domaine.image}`)"
                  width="40px"
                  height="28px"
                />
                {{ domaine.name }}
              </nuxt-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="relative bg-gradient-to-r from-blue-800 to-blue-900 overflow-hidden pt-20 pb-6 z-0"
    >
      <div class="hidden sm:block sm:absolute sm:inset-y-0 sm:h-full sm:w-full">
        <div class="relative h-full max-w-screen-xl mx-auto opacity-25">
          <img
            class="hidden lg:block absolute left-full transform -translate-x-3/4 -translate-y-20 opacity-75"
            src="@/assets/images/france_dark.svg"
            width="1530"
            alt="france engagement"
          />
        </div>
      </div>

      <div class="relative pb-12">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6">
          <div class="text-center mb-10">
            <h2
              class="text-3xl leading-9 tracking-tight font-bold text-white sm:text-5xl sm:leading-10"
            >
              Rejoignez le mouvement
              <span class="text-blue-200">#JeVeuxAider</span>
            </h2>
            <p
              class="mt-4 mb-8 text-center max-w-4xl text-2xl leading-7 text-blue-200 lg:mx-auto"
            >
              Des milliers de bénévoles, associations et collectivités en font
              déjà partie.
            </p>
          </div>
        </div>
        <div
          class="max-w-screen-xl mx-auto md:grid gap-6 md:grid-cols-2 px-4 md:px-6 lg:px-8"
        >
          <div
            class="p-14 pb-10 md:flex md:flex-col bg-white rounded-lg shadow-lg"
          >
            <blockquote class="md:flex-grow md:flex md:flex-col">
              <div
                class="relative text-lg leading-7 font-medium text-gray-800 md:flex-grow"
              >
                <svg
                  class="absolute top-0 left-0 transform -translate-x-3 -translate-y-3 h-8 w-8 text-gray-300 opacity-50"
                  fill="currentColor"
                  viewBox="0 0 32 32"
                >
                  <path
                    d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"
                  />
                </svg>
                <p class="relative">
                  J’ai eu la chance de pouvoir me rendre utile en faisant une
                  distribution de repas pour les SDF de Nancy. Une expérience
                  qui ne laisse pas indifférent … J'ai des souvenirs plein la
                  tête. Maintenant je continue mon chemin avec la Réserve
                  Civique en participant à différentes missions. Je me sens
                  utile et citoyenne !
                </p>
              </div>
              <footer class="mt-6">
                <div class="flex">
                  <div class="flex-shrink-0 inline-flex rounded-full">
                    <img
                      class="h-14 w-14 mt-1 rounded-full shadow-lg"
                      src="@/assets/images/sante-3.jpg"
                      alt="domaine sante"
                      width="56px"
                      height="56px"
                    />
                  </div>
                  <div class="ml-5">
                    <div class="text-base leading-6 font-medium text-gray-900">
                      Nathalie
                      <span
                        class="text-base leading-6 font-medium text-gray-400"
                      >
                        · Bénévole</span
                      >
                    </div>
                    <img
                      class="h-9"
                      src="@/assets/images/logo_banquealimentaire.png"
                      alt="logo banque alimentaire"
                      width="90px"
                      height="36px"
                    />
                  </div>
                </div>
              </footer>
            </blockquote>
          </div>
          <div
            class="p-14 pb-10 mt-4 md:mt-0 md:flex md:flex-col bg-white rounded-lg shadow-lg"
          >
            <blockquote class="md:flex-grow md:flex md:flex-col">
              <div
                class="relative text-lg leading-7 font-medium text-gray-800 md:flex-grow"
              >
                <svg
                  class="absolute top-0 left-0 transform -translate-x-3 -translate-y-3 h-8 w-8 text-gray-300 opacity-50"
                  fill="currentColor"
                  viewBox="0 0 32 32"
                >
                  <path
                    d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"
                  />
                </svg>
                <p class="relative">
                  La puissance de mobilisation de JeVeuxAider est
                  impressionnante ! Nous avons reçu 170 nouvelles candidatures
                  de bénévoles en moins de 6 semaines. JeVeuxAider est
                  clairement l’un des meilleurs canaux en termes d’efficacité du
                  temps investi et de nombre de retours.
                </p>
              </div>
              <footer class="mt-6">
                <div class="flex">
                  <div class="flex-shrink-0 inline-flex rounded-full">
                    <img
                      class="mt-0 rounded-full shadow-lg"
                      src="@/assets/images/clement.jpeg"
                      alt="candidature bénévole"
                      width="56px"
                      height="56px"
                    />
                  </div>
                  <div class="ml-5">
                    <div class="text-base leading-6 font-medium text-gray-900">
                      Clément Debosque
                      <span
                        class="text-base leading-6 font-medium text-gray-400"
                      >
                        · Responsable</span
                      >
                    </div>
                    <img
                      class="h-6"
                      src="@/assets/images/lafourmiliere.png"
                      alt="logo la fourmiliere"
                      width="138px"
                      height="24px"
                    />
                  </div>
                </div>
              </footer>
            </blockquote>
          </div>
        </div>
      </div>
    </div>

    <div class="relative bg-gray-100 pt-16 pb-28 px-4 sm:px-6 lg:px-8">
      <div class="relative max-w-6xl mx-auto">
        <div class="text-center">
          <h2
            class="text-3xl leading-9 tracking-tight font-bold text-gray-900 sm:text-5xl sm:leading-10"
          >
            Restez informé
          </h2>
          <p
            class="mt-3 max-w-2xl mx-auto text-xl leading-7 text-gray-500 sm:mt-4"
          >
            Les actualités de l’engagement sur le blog de l'engagement
          </p>
        </div>
        <div
          class="mt-12 grid container gap-6 mx-auto lg:grid-cols-3 lg:max-w-none"
        >
          <div
            v-for="actualite in actualites"
            :key="actualite.title"
            class="flex flex-col rounded-lg shadow-lg overflow-hidden"
          >
            <div class="flex-shrink-0">
              <img
                :src="require(`@/assets/images/dynamic/${actualite.image}`)"
                :alt="actualite.title"
                class="h-48 w-full object-cover"
                width="554px"
                height="312px"
              />
            </div>
            <div class="flex-1 bg-white p-8 flex flex-col justify-between">
              <div class="flex-1">
                <p class="text-sm leading-5 font-medium text-blue-800">
                  <a
                    target="_blank"
                    rel="noopener"
                    href="https://jeveuxaider.gouv.fr/engagement/actualites/"
                    class="text-base leading-2 font-semibold tracking-wide uppercase text-gray-500 hover:text-blue-800"
                  >
                    {{ actualite.type }}
                  </a>
                </p>
                <a
                  target="_blank"
                  rel="noopener"
                  :href="actualite.link"
                  class="block"
                  ><h3
                    class="mt-2 text-2xl leading-7 font-bold text-gray-800 tracking-tight"
                  >
                    {{ actualite.title }}
                  </h3>
                  <p class="mt-3 text-base leading-6 text-gray-500">
                    {{ actualite.teaser }}
                  </p></a
                >
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-12 pb-6">
        <a
          href="https://jeveuxaider.gouv.fr/engagement/actualites/"
          target="_blank"
          rel="noopener"
          class="m-3 inline-flex px-6 py-3 rounded-full text-base leading-8 font-semibold tracking-wide uppercase bg-white shadow-lg text-gray-800 hover:scale-105 transform transition duration-150 ease-in-out hover:text-blue-800"
        >
          Plus d'articles</a
        >
      </div>
    </div>

    <div class="relative pb-10 -mt-20">
      <div class="relative max-w-6xl mx-auto">
        <div class="max-w-screen-xl mx-auto md:grid gap-6 md:grid-cols-2">
          <div
            class="m-2 lg:m-0 p-9 m:p-12 pb-8 md:flex md:flex-col rounded-lg shadow-lg bg-gradient-to-r from-blue-900 to-blue-800"
          >
            <h2
              class="text-3xl leading-9 font-bold tracking-tight text-white sm:text-4xl sm:leading-10"
            >
              <span class="block"
                >Un e-mail par mois pour plus d'engagement</span
              >
            </h2>
            <p
              id="newsletter-headline"
              class="mt-3 max-w-3xl text-lg leading-6 text-blue-200"
            >
              Chaque mois, nous vous proposons de nouvelles missions de
              bénévolat à distance ou près de chez vous.
            </p>
            <form
              v-if="!successNewsletter"
              class="sm:flex mt-9"
              aria-labelledby="newsletter-headline"
            >
              <div class="w-full">
                <input
                  v-model="email"
                  aria-label="Email address"
                  type="email"
                  required
                  class="w-full px-5 py-3 appearance-none shadow-lg border border-transparent text-lg leading-6 rounded-full text-gray-900 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 transition duration-150 ease-in-out"
                  placeholder="Votre e-mail"
                />
                <div v-if="emailError" class="text-red-600 font-semibold">
                  {{ emailError }}
                </div>
              </div>
              <div
                class="mt-3 rounded-full shadow-lg sm:mt-0 sm:ml-3 sm:flex-shrink-0"
              >
                <button
                  class="w-full flex items-center justify-center px-6 py-3 text-lg leading-8 font-medium rounded-full shadow-lg text-white bg-green-400 hover:text-white hover:scale-105 transform transition duration-150 ease-in-out"
                  @click.prevent="handleSubmitNewsletter(email)"
                >
                  S'inscrire
                </button>
              </div>
            </form>
            <div
              v-if="!successNewsletter"
              class="mt-2 ml-6 text-sm leading-5 text-blue-200 text-center lg:text-left"
            >
              Nous veillons à respecter votre vie privée
            </div>
            <div
              v-if="successNewsletter"
              class="text-white font-semibold text-xl mt-6"
            >
              Merci! Vous avez bien été ajouté à la Newsletter.
            </div>
          </div>
          <div
            class="m-2 lg:m-0 p-9 m:p-12 rounded-lg shadow-lg bg-gradient-to-r from-blue-800 to-blue-900"
          >
            <h2
              class="text-3xl leading-9 font-bold tracking-tight text-white sm:text-4xl sm:leading-10"
            >
              <span class="block"
                >Echangez avec la communauté #JeVeuxAider</span
              >
            </h2>
            <p
              id="facebook-headline"
              class="mt-3 max-w-3xl text-lg leading-6 text-blue-200"
            >
              Posez toutes vos questions aux bénévoles et à l'équipe dans le
              groupe Facebook
            </p>
            <a
              href="https://www.facebook.com/jeveuxaider.gouv.fr"
              target="_blank"
              rel="noopener"
              class="mt-9 bg-white border border-transparent rounded-full shadow-lg py-3 px-5 inline-flex items-center text-lg leading-7 font-medium text-blue-800 hover:scale-105 transform transition duration-150 ease-in-out"
            >
              <svg
                class="mr-2"
                fill="currentColor"
                viewBox="0 0 24 24"
                width="24px"
                height="24px"
              >
                <path
                  fill-rule="evenodd"
                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                  clip-rule="evenodd"
                />
              </svg>
              Rejoindre le groupe</a
            >
          </div>
        </div>
      </div>
    </div>

    <div class="relative bg-gray-50 mt-10 border-b border-t border-gray-200">
      <div class="relative lg:grid lg:grid-cols-2 gap-1 max-w-6xl mx-auto">
        <div class="lg:col-start-1 border-r border-gray-200">
          <div class="p-8 lg:p-15">
            <div
              class="ml-1 h-12 opacity-50 mb-4 svg-h-100"
              v-html="
                require('@/assets/images/dynamic/helping-hand.svg?include')
              "
            />
            <p
              class="leading-6 text-blue-800 font-semibold tracking-wide uppercase"
            >
              associations et organisations publiques
            </p>
            <h1
              class="mt-2 mb-6 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
            >
              Facilitez le recrutement<br class="hidden lg:block" />
              de vos bénévoles
            </h1>
            <ul class="">
              <li class="flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="text-green-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                    width="24px"
                    height="24px"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p class="ml-3 text-xl leading-6 text-gray-700">
                  Inscrivez votre organisation en quelques clics
                </p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="text-green-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                    width="24px"
                    height="24px"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p class="ml-3 text-xl leading-6 text-gray-700">
                  Publiez vos missions de bénévolat
                </p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="text-green-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                    width="24px"
                    height="24px"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p class="ml-3 text-xl leading-6 text-gray-700">
                  Trouvez des bénévoles motivés et disponibles
                </p>
              </li>
            </ul>

            <nuxt-link
              to="/register/responsable"
              class="mt-8 shadow-lg inline-flex items-center justify-center px-6 py-3 text-lg leading-6 font-medium rounded-full bg-blue-800 text-white hover:scale-105 transform transition duration-150 ease-in-out"
            >
              Inscrire mon organisation
            </nuxt-link>
          </div>
        </div>
        <div class="lg:col-start-2">
          <div class="p-8 lg:p-15">
            <div
              class="ml-1 h-12 opacity-50 mb-4 svg-h-100"
              v-html="require('@/assets/images/dynamic/townhall.svg?include')"
            />
            <p
              class="leading-6 text-blue-800 font-semibold tracking-wide uppercase"
            >
              Collectivités et territoires
            </p>
            <h1
              class="mt-2 mb-6 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
            >
              Favorisez l'engagement local<br class="hidden lg:block" />
              de vos citoyens
            </h1>
            <ul class="">
              <li class="flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="text-green-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                    width="24px"
                    height="24px"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p class="ml-3 text-xl leading-6 text-gray-700">
                  Créez une page dédiée à votre collectivité
                </p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="text-green-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                    width="24px"
                    height="24px"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p class="ml-3 text-xl leading-6 text-gray-700">
                  Centralisez les missions des organisations locales
                </p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="text-green-400"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                    width="24px"
                    height="24px"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p class="ml-3 text-xl leading-6 text-gray-700">
                  Encouragez et valorisez l'engagement de vos citoyens
                </p>
              </li>
            </ul>
            <nuxt-link
              to="/collectivite"
              class="mt-8 shadow-lg inline-flex items-center justify-center px-6 py-3 border border-transparent text-lg leading-6 font-medium rounded-full bg-blue-800 text-white hover:scale-105 transform transition duration-150 ease-in-out"
            >
              Inscrire ma collectivité
            </nuxt-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  data() {
    return {
      bgHeroMultipleSizes,
      missions_prioritaires: [
        {
          thematique: 'Solidarité et Insertion',
          thematique_image: require('@/assets/images/dynamic/icon_solidarite_insertion.svg'),
          name:
            'Je distribue des produits de première nécessité et des repas aux plus démunis, dans la rue ou au sein d’établissements.',
          image: require('@/assets/images/dynamic/cover-produit.jpg'),
          link:
            '/missions-benevolat?refinementList[template_title][0]=Aide alimentaire et d%27urgence',
          nb_missions_text: '345 missions disponibles <br><b>en présentiel</b>',
        },
        {
          thematique: 'Solidarité et Insertion',
          thematique_image: require('@/assets/images/dynamic/icon_solidarite_insertion.svg'),
          name:
            'Je maintiens un lien avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)',
          image: require('@/assets/images/dynamic/cover-call.jpg'),
          link:
            '/missions-benevolat?refinementList[template_title][0]=Lien avec les personnes fragiles isolées',
          nb_missions_text:
            '298 missions disponibles <br><b>en présentiel</b> ou <b>à distance</b>',
        },
        {
          thematique: 'Éducation pour tous',
          thematique_image: require('@/assets/images/dynamic/icon_education.svg'),
          name:
            'J’apporte un soutien aux enfants et jeunes, notamment dans les quartiers populaires, zones rurales et territoires fragiles.',
          image: require('@/assets/images/dynamic/cover-ecole.jpg'),
          link:
            '/missions-benevolat?refinementList[template_title][0]=Mentorat d’un enfant ou d’un jeune',
          nb_missions_text:
            '267 missions disponibles <br><b>en présentiel</b> ou <b>à distance</b>',
        },
      ],
      domaines: [
        {
          name: 'Mobilisation Covid19',
          image: 'covid.svg',
          link:
            '/missions-benevolat?refinementList[domaines][0]=Mobilisation covid-19',
        },
        {
          name: 'Prévention et protection',
          image: 'securite.svg',
          link:
            '/missions-benevolat?refinementList[domaines][0]=Prévention et protection',
        },
        {
          name: 'Sport pour tous',
          image: 'sport.svg',
          link:
            '/missions-benevolat?refinementList[domaines][0]=Sport pour tous',
        },
        {
          name: 'Art & Culture pour tous',
          image: 'art.svg',
          link:
            '/missions-benevolat?refinementList[domaines][0]=Art et culture pour tous',
        },
        {
          name: 'Mémoire et citoyenneté',
          image: 'memoire.svg',
          link:
            '/missions-benevolat?refinementList[domaines][0]=Mémoire et citoyenneté',
        },
        {
          name: 'Coopération internationale',
          image: 'cooperation.svg',
          link:
            '/missions-benevolat?refinementList[domaines][0]=Coopération internationale',
        },
      ],
      actualites: [
        {
          title: 'Giving Tuesday : libérons notre générosité !',
          link:
            'https://jeveuxaider.gouv.fr/engagement/actualite/giving-tuesday-liberons-notre-generosite/',
          image: 'Honrizontal-1-uai-554x312.png',
          teaser:
            "Décembre, c'est le mois du don chez JeVeuxAider. Quoi de mieux que le Giving Tuesday, ce 1er décembre, pour bien démarrer ?",
          type: 'Actualités',
        },
        {
          title: 'Paul, bénévole pour la Mission Camionnette',
          link:
            'https://jeveuxaider.gouv.fr/engagement/temoignage/paul-benevole-pour-la-mission-camionnette-la-faim-nest-pas-que-physique-elle-est-aussi-soif-de-dialogue-damitie/',
          image: 'o-uai.jpg',
          teaser:
            "Pourquoi et comment s'engager pour l'aide alimentaire ? Paul Piron, 24 ans, professeur de français dans le Val d'Oise et...",
          type: 'Témoignages',
        },
        {
          title:
            "[Parole d'asso] L’Agence du Don en Nature, solidaire et anti-gaspi",
          link:
            'https://jeveuxaider.gouv.fr/engagement/actualite/lagence-du-don-en-nature-contre-la-precarite-et-le-gaspillage/',
          image: 'GovinSorel.jpg',
          teaser:
            "Que fait-on des produits non-alimentaires lorsqu'ils ne sont pas vendus ? Depuis 2008, l'Agence du Don en Nature se propose de..",
          type: 'Focus',
        },
      ],
      email: '',
      emailError: '',
      successNewsletter: false,
      strings: [
        'aider',
        'vivre des moments forts',
        'une société solidaire',
        'me rendre utile',
        'faire vivre les valeurs de la République',
      ],
    }
  },
  head() {
    return {
      title:
        'Je Veux Aider | Devenez bénévole dans une association en quelques clics | La plateforme publique du bénévolat par la Réserve Civique',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            "Trouvez une mission de bénévolat dans une association, organisation publique ou une commune, partout en France, sur le terrain ou à distance. 50 000 places disponibles dans 10 domaines d'action : solidarité, insertion, éducation, environnement, santé, sport, culture ...",
        },
      ],
    }
  },
  methods: {
    handleSubmitNewsletter(email) {
      // eslint-disable-next-line
      const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      if (re.test(String(email).toLowerCase())) {
        this.$axios
          .post('/api/sendinblue/contact', { email })
          .then(() => {
            this.successNewsletter = true
          })
          .catch((error) => {
            console.log(error)
            this.emailError = 'Message erreur !'
          })
      } else {
        this.emailError = "L'email renseigné n'est pas valide"
      }
      setTimeout(() => (this.emailError = ''), 5000)
    },
  },
}
</script>

<style lang="sass" scoped>
.hide-br
  br
    @apply hidden
      @screen lg
        @apply inline

::v-deep .typed-cursor
  font-weight: 100
</style>
