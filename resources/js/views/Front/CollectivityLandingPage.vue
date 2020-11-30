<template>
  <div>
    <div class="bg-blue-800">
      <div class="relative">
        <div class="relative pt-6 pb-12 lg:pb-10">
          <div class="mt-10 mx-auto max-w-screen-xl px-4 sm:px-6 md:mt-16">
            <div class="text-center">
              <h2
                class="text-4xl tracking-tight leading-10 font-bold text-white sm:text-4xl sm:leading-none md:text-5xl"
              >
                Collectivités, faites vivre la Réserve Civique
                <br />

                au sein de votre territoire
              </h2>
              <p
                class="mt-3 max-w-md mx-auto text-base text-white sm:text-lg md:mt-5 md:text-xl md:max-w-3xl"
              >
                La Réserve Civique met à disposition de votre collectivité une
                <b>page dédiée</b> permettant de centraliser les
                <b>missions de vos associations et organisations publique </b>
                sur votre territoire afin de valoriser et promouvoir
                l'engagement local.
              </p>

              <div
                class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8"
              >
                <div class="rounded-full shadow-lg">
                  <router-link
                    to="/register/responsable"
                    class="flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10"
                  >
                    Inscrivez votre collectivité
                  </router-link>
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
          <div class="max-w-screen-xl mx-auto px-6 sm:px-20">
            <img
              class="relative rounded-lg shadow-lg"
              src="/images/screen.jpg"
              alt="App screenshot"
            />
          </div>
        </div>
      </div>
      <div class="bg-gray-100">
        <div
          class="max-w-screen-xl mx-auto pt-16 pb-20 px-4 sm:px-6 md:pb-24 lg:px-8"
        >
          <h3
            class="text-center text-gray-400 text-sm font-semibold uppercase tracking-wide"
          >
            parmi les collectivités déjà inscrites
          </h3>
          <div
            class="mt-8 grid grid-cols-2 gap-8 md:grid-cols-4 lg:grid-cols-4"
          >
            <div
              class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1"
            >
              <img class="h-14" src="/images/saint-maurice.png" />
            </div>
            <div
              class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1"
            >
              <img class="h-14" src="/images/ouistreham.png" />
            </div>
            <div
              class="col-span-1 flex justify-center md:col-span-2 lg:col-span-1"
            >
              <img class="h-14" src="/images/logo-pays-grasse.png" />
            </div>
            <div
              class="col-span-1 flex justify-center md:col-span-3 lg:col-span-1"
            >
              <img class="h-14" src="/images/soissons.png" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-gray-50 overflow-hidden">
      <div
        class="relative max-w-xl mx-auto px-4 sm:px-6 lg:px-8 lg:max-w-screen-xl"
      >
        <div class="lg:text-center mt-20">
          <h3
            class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
          >
            Pourquoi créer votre page collectivité ?
          </h3>
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
                      class="bg-blue-800 rounded-md p-3 text-white text-center"
                    >
                      <img class="h-7" src="images/treehouse.svg" alt="" />
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
                      class="bg-blue-800 rounded-md p-3 text-white text-center"
                    >
                      <img class="h-7" src="images/alerte.svg" alt="" />
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
                      class="bg-blue-800 rounded-md p-3 text-white text-center"
                    >
                      <img class="h-7" src="images/users.svg" alt="" />
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
              src="images/france.png"
              alt=""
            />
          </div>
        </div>
      </div>
    </div>
    <div class="py-20 bg-gray-100 overflow-hidden shadow-lg">
      <div
        class="relative max-w-xl mx-auto px-6 sm:px-6 lg:px-8 lg:max-w-screen-xl"
      >
        <div class="mt-4bg-gray-50 overflow-hidden">
          <div
            class="relative max-w-xl mx-auto px-8 sm:px-6 lg:px-8 lg:max-w-screen-xl"
          >
            <div class="lg:text-center">
              <h3
                class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
              >
                Rejoignez le mouvement
                <br class="xl:hidden" />
                <span class="text-blue-800">#RéserveCivique</span>
              </h3>
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
                  src="images/avatar-1.jpg"
                  alt=""
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="images/avatar-7.jpg"
                  alt=""
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="images/avatar-3.jpg"
                  alt=""
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="images/avatar-9.jpg"
                  alt=""
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="images/avatar-6.jpg"
                  alt=""
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="images/avatar-5.jpg"
                  alt=""
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="images/avatar-8.jpg"
                  alt=""
                />
                <img
                  class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
                  src="images/avatar-2.jpg"
                  alt=""
                />
              </div>

              <dl
                class="mt-12 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
              >
                <div class="flex flex-col mt-10 sm:mt-0">
                  <dt
                    class="order-2 mt-2 text-lg leading-6 font-medium text-gray-800"
                  >
                    Bénévoles
                  </dt>
                  <dd
                    class="order-1 text-5xl leading-none font-bold text-gray-800"
                  >
                    313 980
                  </dd>
                </div>
                <div class="flex flex-col mt-10 sm:mt-0">
                  <dt
                    class="order-2 mt-2 text-lg leading-6 font-medium text-gray-800"
                  >
                    Organisations
                  </dt>
                  <dd
                    class="order-1 text-5xl leading-none font-bold text-gray-800"
                  >
                    3 878
                  </dd>
                </div>

                <div class="flex flex-col mt-10 sm:mt-0">
                  <dt
                    class="order-2 mt-2 text-lg leading-6 font-medium text-gray-800"
                  >
                    Mises en relation
                  </dt>
                  <dd
                    class="order-1 text-5xl leading-none font-bold text-gray-800"
                  >
                    107 528
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-blue-800 border-b border-gray-200">
      <div
        class="max-w-screen-lg mx-auto py-12 px-18 sm:px-16 lg:py-16 lg:flex lg:items-center lg:justify-between text-center"
      >
        <h2
          class="text-3xl leading-9 font-bold tracking-tight text-white sm:text-4xl sm:leading-10 text-center"
        >
          Donnez vie à l'engagement local
        </h2>
        <router-link
          to="/register/responsable"
          class="mt-4 lg:mt-0 inline-flex shadow-lg items-center text-center justify-center px-7 py-3 border border-transparent text-lg leading-6 font-medium rounded-full border hover:bg-red-700 hover:text-white bg-red-600 text-white transition duration-150 ease-in-out"
        >
          Rejoignez la Réserve Civique
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CollectivityLandindPage',
}
</script>
