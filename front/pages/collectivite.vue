<template>
  <div>
    <div class="bg-[#070191]">
      <div class="relative">
        <div class="relative pt-6 pb-12 lg:pb-10">
          <div class="mt-10 mx-auto max-w-[1280px] px-4 sm:px-6 md:mt-16">
            <div class="text-center">
              <h1
                class="text-4xl tracking-tight leading-10 font-bold text-white sm:text-4xl sm:leading-none md:text-5xl max-w-5xl mx-auto"
              >
                Collectivités, faites vivre JeVeuxAider.gouv.fr au sein de votre
                territoire
              </h1>
              <p
                class="mt-3 max-w-md mx-auto text-base text-white sm:text-lg md:mt-5 md:text-xl md:max-w-3xl"
              >
                JeVeuxAider.gouv.fr met à disposition de votre collectivité une
                <b>page dédiée</b> permettant de centraliser les
                <b>missions de vos associations et organisations publique </b>
                sur votre territoire afin de valoriser et promouvoir
                l'engagement local.
              </p>

              <div
                class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8"
              >
                <div class="rounded-full shadow-lg">
                  <nuxt-link
                    to="/inscription/organisation?orga_type=Collectivité"
                    class="flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white bg-red-600 hover:bg-red-700 !outline-none focus:ring transition md:py-4 md:text-lg md:px-10"
                  >
                    Inscrivez votre collectivité
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="relative">
          <div class="absolute inset-0 flex flex-col">
            <div class="flex-1"></div>
            <div class="flex-1 w-full bg-gray-100"></div>
          </div>
          <div class="max-w-[1280px] mx-auto px-6 sm:px-20">
            <img
              class="relative rounded-lg shadow-lg"
              src="@/assets/images/screen.jpg"
              alt="App screenshot"
            />
          </div>
        </div>
      </div>
      <div class="bg-gray-100">
        <div
          class="max-w-[1280px] mx-auto pt-16 pb-20 px-4 sm:px-6 md:pb-24 lg:px-8"
        >
          <h2
            class="text-center text-gray-400 text-sm font-semibold uppercase tracking-wide"
          >
            parmi les collectivités déjà inscrites
          </h2>
          <div
            class="mt-8 grid grid-cols-2 gap-8 md:grid-cols-4 lg:grid-cols-4"
          >
            <div
              class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1"
            >
              <img
                class="h-14"
                alt="Saint-Maurice"
                src="@/assets/images/saint-maurice.png"
              />
            </div>
            <div
              class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1"
            >
              <img
                class="h-14"
                alt="Ouistreham"
                src="@/assets/images/ouistreham.png"
              />
            </div>
            <div
              class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1"
            >
              <img
                class="h-14"
                alt="Pays de Grasse"
                src="@/assets/images/logo-pays-grasse.png"
              />
            </div>
            <div
              class="col-span-1 flex justify-center md:col-span-3 lg:col-span-1"
            >
              <img
                class="h-14"
                alt="Soissons"
                src="@/assets/images/soissons.png"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-gray-50 overflow-hidden">
      <div
        class="relative max-w-xl mx-auto px-4 sm:px-6 lg:px-8 lg:max-w-[1280px]"
      >
        <div class="lg:text-center mt-20">
          <h2
            class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
          >
            Pourquoi créer votre page collectivité ?
          </h2>
        </div>

        <div
          class="relative lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center mt-8 lg:mt-0"
        >
          <div class="relative">
            <ul class="">
              <li>
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="bg-[#070191] rounded-md p-3 text-white text-center"
                    >
                      <img
                        class="h-7"
                        src="@/assets/images/treehouse.svg"
                        alt=""
                      />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-xl leading-6 font-bold text-gray-900">
                      Pour la collectivité,
                    </h5>
                    <p class="text-base leading-6 text-gray-500">
                      C'est l'opportunité de dynamiser la vie associative locale
                      en mettant à disposition des acteurs publics du
                      territoire, des associations et des citoyens, un point de
                      rencontre.
                    </p>
                  </div>
                </div>
              </li>
              <li class="mt-10">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="bg-[#070191] rounded-md p-3 text-white text-center"
                    >
                      <img
                        class="h-7"
                        src="@/assets/images/alerte.svg"
                        alt=""
                      />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-xl leading-6 font-bold text-gray-900">
                      Pour les associations et acteurs publics,
                    </h5>
                    <p class="text-base leading-6 text-gray-500">
                      C'est un outil leur permettant de simplifier le
                      recrutement de bénévoles et ainsi de se concentrer sur
                      leur coeur d'activité.
                    </p>
                  </div>
                </div>
              </li>
              <li class="mt-10">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="bg-[#070191] rounded-md p-3 text-white text-center"
                    >
                      <img class="h-7" src="@/assets/images/users.svg" alt="" />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-xl leading-6 font-bold text-gray-900">
                      Pour les citoyens,
                    </h5>
                    <p class="text-base leading-6 text-gray-500">
                      C'est la plateforme pour trouver une mission sur mesure
                      selon leurs envies d'engagement.
                    </p>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <div class="mt-12 mb-8 -mx-4 relative lg:mt-12">
            <img
              class="relative mx-auto"
              width="520"
              src="@/assets/images/france.png"
              alt="France"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="py-20 bg-gray-100 overflow-hidden shadow-lg">
      <div
        class="relative max-w-xl mx-auto px-6 sm:px-6 lg:px-8 lg:max-w-[1280px]"
      >
        <div class="mt-4bg-gray-50 overflow-hidden">
          <div
            class="relative max-w-xl mx-auto px-8 sm:px-6 lg:px-8 lg:max-w-[1280px]"
          >
            <div class="lg:text-center">
              <h2
                class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
              >
                Rejoignez le mouvement
                <br class="xl:hidden" />
                <span class="text-[#070191]">#JeVeuxAider</span>
              </h2>
              <p
                class="mt-4 text-center max-w-4xl text-xl leading-7 text-gray-500 lg:mx-auto"
              >
                Partout en France, des milliers d'acteurs de l'engagement sont
                déjà mobilisés sur le terrain.
              </p>

              <div
                class="relative mt-8 z-0 overflow-hidden text-center mx-auto"
              >
                <img
                  class="relative z-0 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="@/assets/images/avatar-1.jpg"
                  alt="avatar 1"
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="@/assets/images/avatar-7.jpg"
                  alt="avatar 7"
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="@/assets/images/avatar-3.jpg"
                  alt="avatar 3"
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="@/assets/images/avatar-9.jpg"
                  alt="avatar 9"
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="@/assets/images/avatar-6.jpg"
                  alt="avatar 6"
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="@/assets/images/avatar-5.jpg"
                  alt="avatar 5"
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="@/assets/images/avatar-8.jpg"
                  alt="avatar 8"
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="@/assets/images/avatar-2.jpg"
                  alt="avatar 2"
                />
              </div>

              <dl
                class="mt-12 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
              >
                <div class="flex flex-col mt-10 sm:mt-0">
                  <dt
                    class="order-2 mt-2 text-lg leading-6 font-medium text-[#242526]"
                  >
                    Bénévoles
                  </dt>
                  <dd
                    class="order-1 text-5xl leading-none font-bold text-[#242526]"
                  >
                    351 813
                  </dd>
                </div>
                <div class="flex flex-col mt-10 sm:mt-0">
                  <dt
                    class="order-2 mt-2 text-lg leading-6 font-medium text-[#242526]"
                  >
                    Organisations
                  </dt>
                  <dd
                    class="order-1 text-5xl leading-none font-bold text-[#242526]"
                  >
                    7 226
                  </dd>
                </div>

                <div class="flex flex-col mt-10 sm:mt-0">
                  <dt
                    class="order-2 mt-2 text-lg leading-6 font-medium text-[#242526]"
                  >
                    Mises en relation
                  </dt>
                  <dd
                    class="order-1 text-5xl leading-none font-bold text-[#242526]"
                  >
                    187 745
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-[#070191] border-b border-gray-200">
      <div
        class="max-w-[1024px] mx-auto py-12 px-18 sm:px-16 lg:py-16 lg:flex lg:items-center lg:justify-between text-center"
      >
        <h2
          class="text-3xl leading-9 font-bold tracking-tight text-white sm:text-4xl sm:leading-10 text-center"
        >
          Donnez vie à l'engagement local
        </h2>
        <nuxt-link
          to="/inscription/organisation?orga_type=Collectivité"
          class="mt-4 lg:mt-0 inline-flex shadow-lg items-center text-center justify-center px-7 py-3 border-transparent text-lg leading-6 font-medium rounded-full border hover:bg-red-700 hover:text-white bg-red-600 text-white transition"
        >
          Rejoignez JeVeuxAider.gouv.fr
        </nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  head() {
    return {
      title:
        'Faites vivre la solidarité au sein de votre territoire avec JeVeuxAider.gouv.fr, la plateforme publique du bénévolat proposée par la Réserve Civique',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/collectivite',
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            "Trouvez des missions en quelques clics. Devenez bénévole près de chez vous ou à distance. Faites vivre l'engagement de chacun pour tous ! Plus de 350 000 bénévoles sont déjà inscrits.",
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
      ],
    }
  },
}
</script>
