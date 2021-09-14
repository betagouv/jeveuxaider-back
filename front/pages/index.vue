<template>
  <div>
    <div class="relative bg-[#070191] overflow-hidden z-10">
      <div
        class="absolute bottom-0 bg-gradient-to-b from-transparent to-[#070191] z-10 h-32 w-full"
      ></div>

      <img
        class="z-1 object-cover absolute h-screen lg:h-auto"
        alt="Je Veux Aider"
        :srcSet="bgHeroMultipleSizes.srcSet"
        :src="bgHeroMultipleSizes.src"
      />
      <div
        class="p-6 lg:pt-40 lg:pb-40 mt-12 relative w-full lg:inset-y-0 text-center z-10"
      >
        <client-only>
          <template #placeholder>
            <div
              class="mx-auto text-4xl lg:text-5xl tracking-tight leading-10 font-bold text-white md:h-auto"
            >
              Je veux aider
            </div>
          </template>
          <div v-if="hasReduceMotionOrMobile">
            <div
              class="mx-auto text-4xl lg:text-5xl tracking-tight leading-10 font-bold text-white md:h-auto"
            >
              Je veux aider
            </div>
          </div>
          <div v-else>
            <vue-typed-js
              ref="typedJS"
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
          </div>
        </client-only>
        <div
          class="mt-3 max-w-md mx-auto text-white text-xl lg:text-2xl md:mt-2 md:max-w-3xl"
        >
          <h1 class="inline">
            Devenez bénévole et trouvez des missions en quelques clics
          </h1>
          <br class="hidden lg:block" />
          <strong class="font-bold">près de chez vous</strong> ou
          <strong class="font-bold">à distance</strong>
        </div>

        <div class="mt-5">
          <button
            class="mx-auto font-bold cursor-pointer flex items-center shadow-lg justify-center border border-transparent text-2xl leading-6 rounded-full text-white bg-[#16a972] py-4 px-10 pb-5 hover:shadow-lg hover:scale-105 transform transition"
            @click="handleClickCTA()"
          >
            Trouver une mission
          </button>
        </div>
      </div>
    </div>

    <div class="bg-[#070191] pt-6">
      <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <h2
            class="text-3xl leading-9 font-bold tracking-tight text-white sm:text-5xl sm:leading-10"
          >
            Plus de 330 000 bénévoles
          </h2>
          <p class="mt-3 text-xl lg:text-2xl leading-7 text-[#c3ddfd] sm:mt-4">
            soutiennent des milliers d'associations et d'organisations publiques
          </p>
        </div>
      </div>
      <div class="mt-10 pb-12 bg-gray-50">
        <div class="relative">
          <div class="absolute inset-0 h-14 bg-[#070191]"></div>
          <div class="relative max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
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
                    src="@/assets/images/logo-jagis-pour-la-nature.svg"
                    width="100"
                    height="40"
                  />
                </div>
                <div
                  class="flex lg:flex-col border-b border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r justify-center"
                >
                  <img
                    class="m-auto"
                    alt="partenaire engagement Banque Alimentaire"
                    src="@/assets/images/logo_banquealimentaire.png"
                    width="150"
                    height="60"
                  />
                </div>
                <div
                  class="flex flex-col border-b border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r justify-center"
                >
                  <img
                    class="m-auto"
                    src="@/assets/images/logo_emmaus.png"
                    alt="partenaire engagement Emmaus"
                    width="150"
                    height="60"
                  />
                </div>
                <div
                  class="flex flex-col border-b border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r justify-center"
                >
                  <img
                    class="m-auto"
                    src="@/assets/images/logo_aphp.png"
                    alt="partenaire engagement APHP"
                    width="150"
                    height="60"
                  />
                </div>
                <a
                  href="https://www.jeveuxaider.gouv.fr/engagement/organisations/"
                  target="_blank"
                  rel="noopener"
                  class="flex flex-col border-gray-100 py-4 px-6 text-center sm:border-0 sm:border-r uppercase leading-6 text-xs text-[#242526] col-span-2 lg:col-span-1"
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

    <div class="bg-gray-50 pt-6 z-10 relative">
      <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-auto text-center">
          <span
            class="text-2xl lg:text-3xl leading-8 lg:leading-10 block lg:inline-flex font-bold tracking-tight text-[#242526]"
          >
            <span class="block lg:inline-flex">
              Trouvez une mission de bénévolat</span
            >

            <nuxt-link
              to="/missions-benevolat?refinementList[type][0]=Mission en présentiel"
              class="tracking-normal lg:inline-flex mx-4 mt-3 lg:-mt-2 text-[#242526] font-semibold bg-white border border-transparent rounded-full shadow-lg py-4 pb-5 px-5 inline-flex items-center text-2xl lg:text-3xl leading-6 lg:leading-6 hover:scale-105 hover:text-[#070191] transform transition"
            >
              près de chez vous</nuxt-link
            >
            <span class="block lg:inline-flex my-4 lg:my-0">ou</span>

            <nuxt-link
              to="/missions-benevolat?refinementList[type][0]=Mission à distance"
              class="tracking-normal lg:ml-4 lg:inline-flex lg:-mt-2 text-[#242526] font-semibold bg-white border border-transparent rounded-full shadow-lg py-4 pb-5 px-5 inline-flex items-center text-2xl lg:text-3xl leading-6 lg:leading-6 hover:scale-105 hover:text-[#070191] transform transition"
            >
              à distance</nuxt-link
            >
          </span>
        </div>
      </div>
    </div>

    <div class="bg-gray-50 pb-20 px-4 md:px-6 lg:px-8">
      <p
        class="text-center pt-12 pb-2 text-base leading-6 font-semibold uppercase text-gray-600 tracking-wider"
      >
        Parmi les missions prioritaires
      </p>

      <div
        class="mt-10 grid gap-5 max-w-6xl mx-auto lg:grid-cols-3 lg:max-w-none"
      >
        <nuxt-link
          v-for="mission in missions_prioritaires"
          :key="mission.name"
          :to="mission.link"
          class="flex max-w-lg mx-auto flex-col rounded-lg shadow-lg overflow-hidden hover:scale-105 transform transition"
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
                width="600"
                height="286"
              />
            </div>
          </div>
          <div class="flex-1 bg-white p-8 pt-4 flex flex-col justify-between">
            <div class="flex-1">
              <div
                class="text-base leading-10 font-semibold tracking-wide uppercase text-gray-500 hover:text-[#070191]"
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
                  class="hidden sm:block flex-shrink-0 rounded-md p-2 text-center bg-[#070191]"
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
                  class="text-sm leading-5 tracking-tight font-medium text-[#242526]"
                  v-html="mission.nb_missions_text"
                ></p>
              </div>
            </div>
          </div>
        </nuxt-link>
      </div>

      <div class="mt-8">
        <p
          class="text-center pt-10 text-base leading-6 font-semibold uppercase text-gray-600 tracking-wider"
        >
          Partout en france
        </p>

        <div
          class="mt-12 max-w-3xl mx-auto flex flex-wrap gap-4 sm:gap-6 items-center justify-center"
        >
          <nuxt-link
            v-for="(city, index) in hightlightedCities"
            :key="city.name"
            :class="[
              { 'text-gray-400': index == hightlightedCities.length - 1 },
              { 'text-[#242526]': index != hightlightedCities.length - 1 },
            ]"
            class="leading-none truncate px-8 py-4 rounded-full text-sm shadow-md font-extrabold tracking-wide uppercase bg-white transform transition will-change-transform hover:scale-105"
            :to="city.url"
          >
            {{ city.name }}
          </nuxt-link>
        </div>
      </div>
    </div>

    <div
      class="relative bg-gray-100 pt-16 pb-10 px-4 sm:px-6 lg:px-8 shadow-lg z-10"
    >
      <div class="relative max-w-6xl mx-auto">
        <div class="text-center">
          <h2
            class="text-3xl leading-9 tracking-tight font-bold text-[#242526] sm:text-5xl sm:leading-10"
          >
            Trouvez votre domaine d'action
          </h2>
        </div>

        <div
          class="mt-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2 max-w-5xl mx-auto"
        >
          <div
            class="bg-[#f56565] overflow-hidden shadow-lg rounded-lg hover:scale-105 transform transition"
          >
            <div class="bg-white px-4 py-2 sm:px-6">
              <div class="text-sm leading-5">
                <nuxt-link
                  to="/domaines-action/solidarite-et-insertion"
                  class="text-base leading-10 font-semibold tracking-wide uppercase text-[#242526]"
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
            class="bg-[#0e9f6e] overflow-hidden shadow-lg rounded-lg hover:scale-105 transform transition"
          >
            <div class="bg-gray-50 px-4 py-2 sm:px-6">
              <div class="text-sm leading-5">
                <nuxt-link
                  to="/domaines-action/protection-de-la-nature"
                  class="text-base leading-10 font-semibold tracking-wide uppercase text-[#242526]"
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
            class="bg-[#070191] overflow-hidden shadow-lg rounded-lg hover:scale-105 transform transition"
          >
            <div class="bg-gray-50 px-4 py-2 sm:px-6">
              <div class="text-sm leading-5">
                <nuxt-link
                  to="/domaines-action/education-pour-tous"
                  class="text-base leading-10 font-semibold tracking-wide uppercase text-[#242526]"
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
            class="bg-[#553c9a] overflow-hidden shadow-lg rounded-lg hover:scale-105 transform transition"
          >
            <div class="bg-gray-50 px-4 py-2 sm:px-6">
              <div class="text-sm leading-5">
                <nuxt-link
                  to="/domaines-action/sante-pour-tous"
                  class="text-base leading-10 font-semibold tracking-wide uppercase text-[#242526]"
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
      <div class="relative max-w-[1280px] text-center mx-auto">
        <p
          class="text-center pt-10 text-base leading-6 font-semibold uppercase text-gray-600 tracking-wider"
        >
          les autres domaines d'action
        </p>

        <div
          class="py-8 flex flex-wrap items-center justify-center gap-4 sm:gap-6"
        >
          <nuxt-link
            v-for="domaine in domaines"
            :key="domaine.name"
            :to="domaine.link"
            class="inline-flex items-center py-2 px-4 sm:py-3 rounded-full text-xs sm:text-sm lg:text-base leading-8 font-semibold tracking-wide uppercase bg-white shadow-lg text-[#242526] hover:scale-105 transform transition hover:text-[#070191]"
          >
            <img
              :alt="domaine.name"
              class="h-7 pr-3 opacity-50"
              :src="require(`@/assets/images/dynamic/${domaine.image}`)"
              width="40"
              height="28"
            />
            {{ domaine.name }}
          </nuxt-link>
        </div>
      </div>
    </div>

    <div
      class="relative bg-gradient-to-r from-[#070191] to-[#1f0391] overflow-hidden pt-20 pb-6 z-0"
    >
      <div class="hidden sm:block sm:absolute sm:inset-y-0 sm:h-full sm:w-full">
        <div class="relative h-full max-w-[1280px] mx-auto opacity-25">
          <img
            class="hidden lg:block absolute left-full transform -translate-x-3/4 -translate-y-20 opacity-75"
            src="@/assets/images/france_dark.svg"
            width="1530"
            alt="france engagement"
          />
        </div>
      </div>

      <div class="relative pb-12">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6">
          <div class="text-center mb-10">
            <h2
              class="text-3xl leading-9 tracking-tight font-bold text-white sm:text-5xl sm:leading-10"
            >
              Rejoignez le mouvement
              <span class="text-[#c3ddfd]">#JeVeuxAider</span>
            </h2>
            <p
              class="mt-4 mb-8 text-center max-w-4xl text-2xl leading-7 text-[#c3ddfd] lg:mx-auto"
            >
              Des milliers de bénévoles, associations et collectivités en font
              déjà partie.
            </p>
          </div>
        </div>
        <div
          class="max-w-[1280px] mx-auto md:grid gap-6 md:grid-cols-2 px-4 md:px-6 lg:px-8"
        >
          <div
            class="p-14 pb-10 md:flex md:flex-col bg-white rounded-lg shadow-lg"
          >
            <blockquote class="md:flex-grow md:flex md:flex-col">
              <div
                class="relative text-lg leading-7 font-medium text-[#242526] md:flex-grow"
              >
                <svg
                  class="absolute top-0 left-0 transform -translate-x-3 -translate-y-3 h-8 w-8 text-[#d2d6dc] opacity-50"
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
                  <div class="flex-shrink-0 rounded-full">
                    <img
                      class="h-14 w-14 mt-1 rounded-full shadow-lg"
                      src="@/assets/images/sante-3.jpg"
                      alt="domaine sante"
                      width="56"
                      height="56"
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
                      width="90"
                      height="36"
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
                class="relative text-lg leading-7 font-medium text-[#242526] md:flex-grow"
              >
                <svg
                  class="absolute top-0 left-0 transform -translate-x-3 -translate-y-3 h-8 w-8 text-[#d2d6dc] opacity-50"
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
                  <div class="flex-shrink-0 rounded-full">
                    <img
                      class="mt-0 rounded-full shadow-lg"
                      src="@/assets/images/clement.jpeg"
                      alt="candidature bénévole"
                      width="56"
                      height="56"
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
                      width="138"
                      height="24"
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
                width="554"
                height="312"
              />
            </div>
            <div class="flex-1 bg-white p-8 flex flex-col justify-between">
              <div class="flex-1">
                <p class="text-sm leading-5 font-medium text-[#070191]">
                  <a
                    target="_blank"
                    rel="noopener"
                    href="https://jeveuxaider.gouv.fr/engagement/actualites/"
                    class="text-base leading-2 font-semibold tracking-wide uppercase text-gray-500 hover:text-[#070191]"
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
                    class="mt-2 text-2xl leading-7 font-bold text-[#242526] tracking-tight"
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
          class="m-3 inline-flex px-6 py-3 rounded-full text-base leading-8 font-semibold tracking-wide uppercase bg-white shadow-lg text-[#242526] hover:scale-105 transform transition hover:text-[#070191]"
        >
          Plus d'articles</a
        >
      </div>
    </div>

    <div class="relative pb-10 px-4 sm:px-6 lg:px-8">
      <div class="relative max-w-6xl mx-auto">
        <div class="max-w-[1280px] mx-auto grid gap-6 lg:grid-cols-2">
          <div
            class="sm:p-9 p-6 pb-8 md:flex md:flex-col rounded-lg shadow-lg bg-gradient-to-r from-[#1f0391] to-[#070191]"
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
              class="mt-3 max-w-3xl text-lg leading-6 text-[#c3ddfd]"
            >
              Chaque mois, nous vous proposons de nouvelles missions de
              bénévolat à distance ou près de chez vous.
            </p>
            <form
              v-if="!successNewsletter"
              class="sm:flex items-start mt-9 max-w-xl"
              aria-labelledby="newsletter-headline"
            >
              <div class="w-full">
                <input
                  v-model="email"
                  aria-label="Email address"
                  type="email"
                  required
                  class="w-full px-5 py-3 appearance-none shadow-lg rounded-full text-gray-900 bg-white placeholder-gray-500 focus:placeholder-gray-400 transition"
                  placeholder="Votre e-mail"
                />
                <div v-if="emailError" class="text-red-600 font-semibold ml-6">
                  {{ emailError }}
                </div>
              </div>

              <button
                class="w-full sm:w-auto flex items-center justify-center px-6 py-3 font-medium rounded-full shadow-lg text-white bg-[#16a972] hover:text-white mt-3 sm:mt-0 sm:ml-3 sm:flex-shrink-0 hover:scale-105 transform transition"
                @click.prevent="handleSubmitNewsletter(email)"
              >
                S'inscrire
              </button>
            </form>
            <div
              v-if="!successNewsletter"
              class="mt-2 sm:ml-6 text-sm leading-5 text-[#c3ddfd] text-center sm:text-left"
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
            class="sm:p-9 p-6 rounded-lg shadow-lg bg-gradient-to-r from-[#070191] to-[#1f0391]"
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
              class="mt-3 max-w-3xl text-lg leading-6 text-[#c3ddfd]"
            >
              Posez toutes vos questions aux bénévoles et à l'équipe dans le
              groupe Facebook
            </p>
            <a
              href="https://www.facebook.com/jeveuxaider.gouv.fr"
              target="_blank"
              rel="noopener"
              class="mt-9 bg-white border border-transparent rounded-full shadow-lg py-3 px-5 inline-flex items-center sm:text-lg leading-7 font-medium text-[#070191] hover:scale-105 transform transition"
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
              class="leading-6 text-[#070191] font-semibold tracking-wide uppercase"
            >
              associations et organisations publiques
            </p>
            <h2
              class="mt-2 mb-6 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
            >
              Facilitez le recrutement<br class="hidden lg:block" />
              de vos bénévoles
            </h2>
            <ul class="">
              <li class="flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="text-[#16a972]"
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
                    class="text-[#16a972]"
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
                    class="text-[#16a972]"
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
              to="/inscription/organisation"
              class="mt-8 shadow-lg inline-flex items-center justify-center px-6 py-3 sm:text-lg leading-6 font-medium rounded-full bg-[#070191] text-white hover:scale-105 transform transition"
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
              class="leading-6 text-[#070191] font-semibold tracking-wide uppercase"
            >
              Collectivités et territoires
            </p>
            <h2
              class="mt-2 mb-6 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
            >
              Favorisez l'engagement local<br class="hidden lg:block" />
              de vos citoyens
            </h2>
            <ul class="">
              <li class="flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="text-[#16a972]"
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
                    class="text-[#16a972]"
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
                    class="text-[#16a972]"
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
              class="mt-8 shadow-lg inline-flex items-center justify-center px-6 py-3 border border-transparent sm:text-lg leading-6 font-medium rounded-full bg-[#070191] text-white hover:scale-105 transform transition"
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
            '/missions-benevolat?refinementList[template_title][0]=J%27apporte%20une%20aide%20alimentaire%20d%27urgence',
          nb_missions_text: '345 missions disponibles <br><b>en présentiel</b>',
        },
        {
          thematique: 'Solidarité et Insertion',
          thematique_image: require('@/assets/images/dynamic/icon_solidarite_insertion.svg'),
          name:
            'Je maintiens un lien avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)',
          image: require('@/assets/images/dynamic/cover-call.jpg'),
          link:
            '/missions-benevolat?refinementList[template_title][0]=Je%20maintiens%20le%20lien%20avec%20des%20personnes%20fragiles%20isolées',
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
            '/missions-benevolat?refinementList[template_title][0]=J%27assure%20une%20mission%20de%20mentorat%20pour%20un%20enfant%20ou%20un%20jeune',
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
      hightlightedCities: [
        {
          name: 'Paris',
          url: '/villes/paris',
        },
        {
          name: 'Toulouse',
          url: '/villes/toulouse',
        },
        {
          name: 'Lyon',
          url: '/villes/lyon',
        },
        {
          name: 'Marseille',
          url: '/villes/marseille',
        },
        {
          name: 'Bordeaux',
          url: '/villes/bordeaux',
        },
        {
          name: 'Lille',
          url: '/villes/lille',
        },
        {
          name: 'Rennes',
          url: '/villes/rennes',
        },
        {
          name: 'Montpellier',
          url: '/villes/montpellier',
        },
        {
          name: 'Strasbourg',
          url: '/villes/strasbourg',
        },
        {
          name: 'Nice',
          url: '/villes/nice',
        },
        {
          name: 'Rouen',
          url: '/villes/rouen',
        },
        {
          name: 'Angers',
          url: '/villes/angers',
        },
        {
          name: 'Plus de villes',
          url: '/territoires',
        },
      ],
    }
  },
  head() {
    return {
      title:
        'Je Veux Aider | Devenez bénévole dans une association en quelques clics | La plateforme publique du bénévolat par la Réserve Civique',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/',
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            "Trouvez une mission de bénévolat dans une association, organisation publique ou une commune, partout en France, sur le terrain ou à distance. 50 000 places disponibles dans 10 domaines d'action : solidarité, insertion, éducation, environnement, santé, sport, culture ...",
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
        {
          hid: 'facebook-domain-verification',
          name: 'facebook-domain-verification',
          content: '8jnmyx2s1iopvryhthxappg6b3tryp',
        },
      ],
    }
  },
  computed: {
    hasReduceMotionOrMobile() {
      if (this.$device.isMobile) {
        return true
      }
      if (process.client) {
        const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)')
        if (!mediaQuery || mediaQuery.matches) {
          return true
        } else {
          return false
        }
      }
      return false
    },
  },
  beforeDestroy() {
    if (this.$refs.typedJS) {
      this.$refs.typedJS.typedObj.destroy()
    }
  },
  methods: {
    handleSubmitNewsletter(email) {
      // eslint-disable-next-line
      const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      if (re.test(String(email).toLowerCase())) {
        this.$axios
          .post('/sendinblue/contact', { email })
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
    handleClickCTA() {
      window.plausible &&
        window.plausible('Click CTA - Homepage', {
          props: { isLogged: this.$store.getters.isLogged },
        })
      this.$store.commit('toggleSearchOverlay')
    },
  },
}
</script>

<style lang="postcss" scoped>
.hide-br {
  br {
    @apply hidden;
    @screen lg {
      @apply inline;
    }
  }
}

::v-deep .typed-cursor {
  font-weight: 100;
}
</style>
