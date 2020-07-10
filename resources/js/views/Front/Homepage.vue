<template>
  <div class>
    <AppHeader />

    <div class="relative bg-blue-800 overflow-hidden z-0 shadow-lg">
      <div class="container mx-auto">
        <div
          class="relative z-10 pb-0 sm:pb-16 md:pb-10 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-24"
        >
          <div
            class="mt-10 mx-auto px-6 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-2 xl:mt-16"
          >
            <div class="sm:text-center lg:text-left">
              <h1
                class="text-4xl tracking-tight leading-10 font-bold text-white sm:text-5xl sm:leading-none md:text-6xl"
              >
                Engagez-vous dans la Réserve Civique
              </h1>

              <p
                class="mt-3 text-base text-white sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-2xl lg:mx-0"
              >
                Trouvez une mission d’intérêt général auprès d’une organisation
                publique ou associative et faites vivre les valeurs de la
                République.
              </p>

              <div
                class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
              >
                <div class="text-center">
                  <p class="text-xs leading-6 font-medium text-white">
                    Je veux aider
                  </p>
                  <router-link
                    to="/register/volontaire"
                    class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white text-blue-800 hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
                  >
                    Devenir réserviste
                  </router-link>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-6 text-center">
                  <p class="text-xs leading-6 font-medium text-white">
                    Mon organisation a besoin de renfort
                  </p>
                  <router-link
                    :to="
                      $store.getters.isLogged &&
                      $store.getters.contextRole == 'responsable'
                        ? {
                            name: 'MissionFormAdd',
                            params: {
                              structureId:
                                $store.getters.structure_as_responsable.id,
                            },
                          }
                        : '/register/responsable'
                    "
                    class="shadow-lg w-full flex items-center justify-center px-8 py-3 border border-transparent border text-base leading-6 font-medium rounded-full text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-9"
                  >
                    Proposer une mission
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="mt-10 p-12 object-cover" src="/images/success.png" alt="" />
      </div>
    </div>

    <div class="py-20 bg-gray-50 overflow-hidden shadow-lg">
      <div class="relative container mx-auto px-6 sm:px-6 lg:px-8">
        <img
          class="hidden lg:block absolute top-0 right-0 transform -translate-x-3/4 translate-y-1 opacity-50"
          src="/images/france.svg"
          width="904"
          alt=""
        />

        <div class="relative">
          <h3
            class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
          >
            Faites vivre l’engagement
            <br class="xl:hidden" />
            <span class="text-blue-800">#FranceUnie</span>
          </h3>
          <p
            class="mt-4 max-w-5xl mx-auto text-center text-xl leading-7 text-gray-500"
          >
            Rejoignez la Réserve Civique et soutenez de grandes causes, dans
            tous les territoires.
          </p>
        </div>

        <div class="relative">
          <div class="mx-auto py-12 lg:py-8">
            <p
              class="text-center text-base leading-6 font-semibold uppercase text-gray-500 tracking-wider"
            >
              Trouvez votre domaine d'action
            </p>
            <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-4 lg:mt-8">
              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/covid.svg"
                  alt=""
                />
                <span class="font-semibold">Mobilisation Covid19</span>
              </div>

              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/sante.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800">Santé pour tous</span>
              </div>

              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/securite.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Prévention et protection</span
                >
              </div>

              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/education.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Education pour tous</span
                >
              </div>
              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/sport.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Sport pour tous
                </span>
              </div>

              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/environnement.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Protection de la nature
                </span>
              </div>

              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/art.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Art & Culture pour tous
                </span>
              </div>

              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/vivre-ensemble.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Solidarité et insertion
                </span>
              </div>
              <div
                class="col-span-1 lg:col-start-2 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/memoire.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Mémoire et citoyenneté
                </span>
              </div>
              <div
                class="col-span-1 flex flex-col justify-center items-center text-center p-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:text-gray-900 lg:flex-row"
              >
                <img
                  class="h-7 pr-3 opacity-50 mb-2 lg:mb-0"
                  style="width: 40px;"
                  src="/images/cooperation.svg"
                  alt=""
                />
                <span class="font-semibold text-gray-800"
                  >Coopération internationale
                </span>
              </div>
            </div>
          </div>
        </div>

        <div
          class="relative mt-2 lg:mt-8 lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center"
        >
          <div class="relative">
            <h4
              class="text-2xl leading-8 font-bold text-gray-900 tracking-tight sm:text-3xl sm:leading-9"
            >
              Les missions prioritaires
              <span class="text-blue-800">#Covid19</span>
            </h4>

            <ul class="mt-10 pr-12">
              <li>
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex-shrink-0 bg-blue-800 rounded-lg p-3 text-white text-center"
                    >
                      <img
                        class="h-7"
                        src="/images/groceries.svg"
                        alt=""
                        style="width: 28px;"
                      />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg leading-6 font-semibold text-gray-900">
                      Aide alimentaire et d’urgence
                    </h5>
                    <p class="mt-2 text-base leading-6 text-gray-500">
                      Je distribue des produits de première nécessité (aliments,
                      hygiène…) et des repas aux plus démunis.
                    </p>
                  </div>
                </div>
              </li>
              <li class="mt-10">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex-shrink-0 bg-blue-800 rounded-lg p-3 text-white text-center"
                    >
                      <img
                        class="h-7"
                        src="/images/teddy-bear.svg"
                        alt=""
                        style="width: 28px;"
                      />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg leading-6 font-semibold text-gray-900">
                      Garde exceptionnelle d’enfants
                    </h5>
                    <p class="mt-2 text-base leading-6 text-gray-500">
                      J’aide à garder des enfants de soignants ou d’une
                      structure de l’Aide Sociale à l’Enfance.
                    </p>
                  </div>
                </div>
              </li>
              <li class="mt-10">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex-shrink-0 bg-blue-800 rounded-lg p-3 text-white text-center"
                    >
                      <img
                        class="h-7"
                        src="/images/phone-handle.svg"
                        alt=""
                        style="width: 28px;"
                      />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg leading-6 font-semibold text-gray-900">
                      Lien avec les personnes fragiles isolées.
                    </h5>
                    <p class="mt-2 text-base leading-6 text-gray-500">
                      Je participe à maintenir le lien avec des personnes
                      fragiles isolées.
                    </p>
                  </div>
                </div>
              </li>
              <li class="mt-10">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex-shrink-0 bg-blue-800 rounded-lg p-3 text-white text-center"
                    >
                      <img
                        class="h-7"
                        src="/images/basket.svg"
                        alt=""
                        style="width: 28px;"
                      />
                    </div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg leading-6 font-semibold text-gray-900">
                      Solidarité de proximité.
                    </h5>
                    <p class="mt-2 text-base leading-6 text-gray-500">
                      Je fais les courses de produits essentiels pour mes
                      voisins les plus fragiles.
                    </p>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <div class="mt-10 -mx-4 relative lg:mt-0">
            <svg
              class="absolute left-1/2 transform -translate-x-1/2 translate-y-16 lg:hidden sm:hidden md:hidden"
              width="784"
              height="404"
              fill="none"
              viewBox="0 0 784 404"
            >
              <defs>
                <pattern
                  id="ca9667ae-9f92-4be7-abcb-9e3d727f2941"
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
                  />
                </pattern>
              </defs>
              <rect
                width="784"
                height="404"
                fill="url(#ca9667ae-9f92-4be7-abcb-9e3d727f2941)"
              />
            </svg>

            <div class="mx-4 mt-10" width="520">
              <img
                class="relative mx-auto rounded-lg"
                src="/images/missions-prio.png"
                alt=""
              />
            </div>
          </div>
        </div>

        <svg
          class="hidden lg:block absolute right-full transform translate-x-1/2 translate-y-12"
          width="404"
          height="784"
          fill="none"
          viewBox="0 0 404 784"
        >
          <defs>
            <pattern
              id="64e643ad-2176-4f86-b3d7-f2c5da3b6a6d"
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
              />
            </pattern>
          </defs>
          <rect
            width="404"
            height="784"
            fill="url(#64e643ad-2176-4f86-b3d7-f2c5da3b6a6d)"
          />
        </svg>

        <div class="relative mt-12 sm:mt-16 lg:mt-8">
          <div
            class="lg:grid lg:grid-flow-row-dense lg:grid-cols-2 lg:gap-8 lg:items-center"
          >
            <div class="lg:col-start-2">
              <ul class="mt-10">
                <li>
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <div
                        class="flex-shrink-0 bg-blue-800 rounded-lg p-3 text-white text-center"
                      >
                        <img
                          class="h-7"
                          src="/images/ecole.svg"
                          alt=""
                          style="width: 28px;"
                        />
                      </div>
                    </div>
                    <div class="ml-4">
                      <h5 class="text-lg leading-6 font-semibold text-gray-900">
                        Soutien scolaire.
                      </h5>
                      <p class="mt-2 text-base leading-6 text-gray-500">
                        J’aide les élèves à étudier et faire leurs devoirs.
                      </p>
                    </div>
                  </div>
                </li>
                <li class="mt-10 pr-12">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <div
                        class="flex-shrink-0 bg-blue-800 rounded-lg p-3 text-white text-center"
                      >
                        <img
                          class="h-7"
                          src="/images/masque-2.svg"
                          alt=""
                          style="width: 28px;"
                        />
                      </div>
                    </div>
                    <div class="ml-4">
                      <h5 class="text-lg leading-6 font-semibold text-gray-900">
                        Fabrication et distribution d’équipements de protection
                        grand public.
                      </h5>
                      <p class="mt-2 text-base leading-6 text-gray-500">
                        Je participe à la confection d’équipements de protection
                        grand public ou à leur distribution.
                      </p>
                    </div>
                  </div>
                </li>
                <li class="mt-10">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <div
                        class="flex-shrink-0 bg-blue-800 rounded-lg p-3 text-white text-center"
                      >
                        <img
                          class="h-7"
                          src="/images/sanitaire.svg"
                          alt=""
                          style="width: 28px;"
                        />
                      </div>
                    </div>
                    <div class="ml-4">
                      <h5 class="text-lg leading-6 font-semibold text-gray-900">
                        Soutien à la mobilisation sanitaire.
                      </h5>
                      <p class="mt-2 text-base leading-6 text-gray-500">
                        J’aide les personnels des établissements de santé ou
                        participe à une action à but sanitaire.
                      </p>
                    </div>
                  </div>
                </li>
                <li class="mt-10">
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <div
                        class="flex-shrink-0 bg-blue-800 rounded-lg p-3 text-white text-center"
                      >
                        <img
                          class="h-7"
                          src="/images/service-public.svg"
                          alt=""
                          style="width: 28px;"
                        />
                      </div>
                    </div>
                    <div class="ml-4">
                      <h5 class="text-lg leading-6 font-semibold text-gray-900">
                        Soutien à la reprise des missions de service public.
                      </h5>
                      <p class="mt-2 text-base leading-6 text-gray-500">
                        Je contribue à la reprise des missions de service public
                        en lien avec la population.
                      </p>
                    </div>
                  </div>
                </li>
              </ul>
            </div>

            <div class="mt-10 relative lg:mt-0 lg:col-start-1">
              <div class="pr-8 mt-10" width="520">
                <img
                  class="relative mx-auto rounded-lg"
                  src="/images/celebrating.png"
                  alt=""
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="relative bg-gray-800">
      <div
        class="h-56 bg-indigo-600 sm:h-72 md:absolute md:left-0 md:h-full md:w-12/12"
      >
        <img
          class="w-full h-full object-cover"
          src="/images/masque-photo.jpg"
          alt="securité covid-19"
        />
      </div>
      <div
        class="relative container mx-auto px-8 py-12 sm:px-6 lg:px-8 lg:py-16"
      >
        <div class="md:w-4/12 md:pl-0">
          <div
            class="text-base leading-6 font-semibold uppercase tracking-wider text-gray-300"
          >
            COVID-19
          </div>
          <h2
            class="mt-2 text-white text-3xl leading-9 font-bold tracking-tight sm:text-4xl sm:leading-10"
          >
            Les 5 règles de sécurité des bénévoles
          </h2>
          <p class="mt-3 text-lg leading-7 text-gray-300">
            Des gestes simples pour préserver votre santé et celle des autres.
          </p>
          <div class="mt-8">
            <div class="inline-flex rounded-md shadow">
              <router-link
                to="/regles-de-securite"
                target="_blank"
                class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-gray-900 bg-white hover:text-gray-600 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
              >
                Consulter les règles de sécurité
                <svg
                  class="-mr-1 ml-3 h-5 w-5 text-gray-400"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5zM5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"
                    clip-rule="evenodd"
                  />
                </svg>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="py-16 bg-gray-50 overflow-hidden lg:py-16">
      <div class="relative container mx-auto px-8 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h3
            class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
          >
            Rejoignez le mouvement
            <br class="xl:hidden" />
            <span class="text-blue-800">#RéserveCivique</span>
          </h3>
          <p
            class="mt-4 text-center max-w-2xl text-xl leading-7 text-gray-500 lg:mx-auto"
          >
            Partout en France, des milliers de réservistes, organisations
            publiques et associations sont déjà mobilisés sur le terrain.
          </p>

          <div class="relative mt-8 z-0 overflow-hidden text-center mx-auto">
            <img
              class="relative z-0 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
              src="/images/avatar-1.jpg"
              alt=""
            />
            <img
              class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
              src="/images/avatar-7.jpg"
              alt=""
            />
            <img
              class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
              src="/images/avatar-3.jpg"
              alt=""
            />
            <img
              class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
              src="/images/avatar-9.jpg"
              alt=""
            />
            <img
              class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
              src="/images/avatar-6.jpg"
              alt=""
            />
            <img
              class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
              src="/images/avatar-5.jpg"
              alt=""
            />
            <img
              class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
              src="/images/avatar-8.jpg"
              alt=""
            />
            <img
              class="relative z-0 -ml-2 inline-block h-10 w-10 rounded-full text-gray-50 shadow-solid"
              src="/images/avatar-2.jpg"
              alt=""
            />
          </div>

          <dl
            v-if="statistics"
            class="mt-12 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
          >
            <div class="flex flex-col mt-10 sm:mt-0">
              <dt
                class="order-2 mt-2 text-lg leading-6 font-medium text-gray-800"
              >
                Réservistes
              </dt>
              <dd class="order-1 text-5xl leading-none font-bold text-gray-800">
                {{ statistics.profiles | formatNumber }}
              </dd>
            </div>
            <div class="flex flex-col mt-10 sm:mt-0">
              <dt
                class="order-2 mt-2 text-lg leading-6 font-medium text-gray-800"
              >
                Organisations
              </dt>
              <dd class="order-1 text-5xl leading-none font-bold text-gray-800">
                {{ statistics.structures | formatNumber }}
              </dd>
            </div>

            <div class="flex flex-col mt-10 sm:mt-0">
              <dt
                class="order-2 mt-2 text-lg leading-6 font-medium text-gray-800"
              >
                Mises en relation
              </dt>
              <dd class="order-1 text-5xl leading-none font-bold text-gray-800">
                {{ statistics.participations | formatNumber }}
              </dd>
            </div>
          </dl>
        </div>
      </div>
    </div>

    <div class="bg-blue-800 border-b border-gray-200">
      <div
        class="container mx-auto flex flex-col items-center justify-center py-12 px-18 sm:px-16 lg:py-16 lg:flex-row lg:justify-between"
      >
        <h2
          class="text-3xl leading-9 font-bold tracking-tight text-white sm:text-4xl sm:leading-10 text-center"
        >
          Votre organisation a besoin de
          <span class="text-blu">bénévoles</span> ?
        </h2>
        <router-link
          :to="
            $store.getters.isLogged &&
            $store.getters.contextRole == 'responsable'
              ? {
                  name: 'MissionFormAdd',
                  params: {
                    structureId: $store.getters.structure_as_responsable.id,
                  },
                }
              : '/register/responsable'
          "
          class="inline-flex mt-3 lg:mt-0 shadow-lg items-center text-center justify-center px-7 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white border border-white hover:bg-blue-800 hover:text-white bg-white text-blue-800 transition duration-150 ease-in-out"
        >
          Publier une mission
        </router-link>
      </div>
    </div>

    <div
      class="relative bg-gray-50 pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8 shadow-lg"
    >
      <div class="relative container mx-auto">
        <div class="text-center">
          <h2
            class="text-3xl leading-9 tracking-tight font-bold text-gray-900 sm:text-5xl sm:leading-10"
          >
            Restez informé
          </h2>
          <p
            class="mt-3 max-w-2xl mx-auto text-xl leading-7 text-gray-500 sm:mt-4"
          >
            L’actualité de l’engagement sur le blog de la <b>Réserve Civique</b>
          </p>
        </div>
        <div
          class="mt-12 grid container gap-8 mx-auto lg:grid-cols-3 lg:max-w-none"
        >
          <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <div class="flex-shrink-0">
              <img
                class="h-48 w-full object-cover"
                src="https://reserve-civique-wp.s3.amazonaws.com/uploads/2020/05/Success-scaled-uai-1032x580.jpg"
                alt=""
              />
            </div>
            <div class="flex-1 bg-white p-10 flex flex-col justify-between">
              <div class="flex-1">
                <p class="text-sm leading-5 font-medium text-blue-800">
                  <a
                    target="_blank"
                    href="https://covid19.reserve-civique.gouv.fr/engagement/actualites/"
                    class="hover:underline"
                  >
                    Actualités
                  </a>
                </p>
                <a
                  target="_blank"
                  href="https://covid19.reserve-civique.gouv.fr/engagement/actualite/mission-accomplie-et-si-on-continuait-ensemble/"
                  class="block"
                >
                  <h3
                    class="mt-2 text-xl leading-7 font-semibold text-gray-900"
                  >
                    Mission accomplie. Et si on continuait ensemble ?
                  </h3>
                  <p class="mt-3 text-base leading-6 text-gray-500">
                    La Réserve Civique s’est massivement mobilisée au pic de la
                    crise sanitaire. Aujourd’hui elle étend son action et
                    devient la plateforme publique de l’engagement.
                  </p>
                </a>
              </div>
            </div>
          </div>

          <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <div class="flex-shrink-0">
              <img
                class="h-48 w-full object-cover"
                src="https://reserve-civique-wp.s3.amazonaws.com/uploads/2020/05/LOGO-RC-carre%CC%81-1-uai-1032x580.jpg"
                alt=""
              />
            </div>
            <div class="flex-1 bg-white p-10 flex flex-col justify-between">
              <div class="flex-1">
                <p class="text-sm leading-5 font-medium text-blue-800">
                  <a
                    target="_blank"
                    href="https://covid19.reserve-civique.gouv.fr/engagement/actualites/"
                    class="hover:underline"
                  >
                    Actualités
                  </a>
                </p>
                <a
                  target="_blank"
                  href="https://covid19.reserve-civique.gouv.fr/engagement/actualite/la-reserve-civique-evolue/"
                  class="block"
                >
                  <h3
                    class="mt-2 text-xl leading-7 font-semibold text-gray-900"
                  >
                    La Réserve Civique évolue !
                  </h3>
                  <p class="mt-3 text-base leading-6 text-gray-500">
                    Missions, domaines d’action, blog … Découvrez toutes les
                    nouveautés de la Réserve Civique, au service de l’engagement
                    de chacun, partout en France.
                  </p>
                </a>
              </div>
            </div>
          </div>

          <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <div class="flex-shrink-0">
              <img
                class="h-48 w-full object-cover"
                src="https://reserve-civique-wp.s3.amazonaws.com/uploads/2020/05/ba-amiens-s2-170420-091757-5156-hd_0-scaled-uai-1032x580.jpg"
                alt=""
              />
            </div>
            <div class="flex-1 bg-white p-10 flex flex-col justify-between">
              <div class="flex-1">
                <p class="text-sm leading-5 font-medium text-blue-800">
                  <a
                    target="_blank"
                    href="https://covid19.reserve-civique.gouv.fr/engagement/category/temoignage/"
                    class="hover:underline"
                  >
                    Témoignage
                  </a>
                </p>
                <a
                  target="_blank"
                  href="https://covid19.reserve-civique.gouv.fr/engagement/temoignage/florian-benevole-a-la-banque-alimentaire-de-dax-dans-les-landes-40/"
                  class="block"
                >
                  <h3
                    class="mt-2 text-xl leading-7 font-semibold text-gray-900"
                  >
                    Florian, bénévole à la Banque Alimentaire de Dax, dans les
                    Landes (40)
                  </h3>
                  <p class="mt-3 text-base leading-6 text-gray-500">
                    « Le rôle de la Banque Alimentaire est crucial : sans elle,
                    les associations n’auraient pas toutes les ressources
                    nécessaires pour aider les plus démunis. »
                  </p>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-10">
        <a
          target="_blank"
          href="https://covid19.reserve-civique.gouv.fr/engagement/actualites/"
          class="inline-flex"
        >
          <button
            type="button"
            class="rounded-md shadow-sm inline-flex items-center px-4 py-2 border border-gray-300 text-base leading-6 font-medium rounded-full text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150"
          >
            Toute l'actualité
          </button>
        </a>
      </div>

      <div class="relative container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative mt-14">
          <img class="mx-auto h-10 w-auto" src="/images/twitter.svg" alt="" />

          <blockquote class="mt-8">
            <div
              class="max-w-3xl mx-auto text-center text-2xl leading-9 font-medium text-gray-700"
            >
              <p>
                &ldquo; Le virus est toujours là. Comment se protéger les uns
                les autres après le confinement ? Quels sont les gestes simples
                à respecter ? Ensemble, protégeons-nous. #CORONAVIRUS #COVID19
                &rdquo;
              </p>
            </div>
            <footer class="mt-8">
              <div class="md:flex md:items-center md:justify-center">
                <div class="md:flex-shrink-0">
                  <img
                    class="mx-auto h-12 w-12 rounded-full shadow-lg"
                    src="/images/logo_carre_rc.jpg"
                    alt=""
                  />
                </div>
                <div
                  class="mt-3 text-center md:mt-0 md:ml-4 md:flex md:items-center"
                >
                  <div class="text-base leading-6 font-medium text-gray-900">
                    Réserve Civique #JeVeuxAider
                  </div>

                  <svg
                    class="hidden md:block mx-1 h-5 w-5 text-blue-500"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M11 0h3L9 20H6l5-20z" />
                  </svg>

                  <div class="text-base leading-6 font-medium text-gray-500">
                    @ReserveCivique
                  </div>
                </div>
              </div>
            </footer>
          </blockquote>
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

<script>
import { statistics } from '@/api/app'

export default {
  name: 'Homepage',
  data() {
    return {
      statistics: null,
    }
  },
  created() {
    statistics('global').then((res) => {
      this.statistics = res.data
    })
  },
}
</script>
