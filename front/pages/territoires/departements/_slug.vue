<template>
  <div>
    <Breadcrumb
      :items="[
        { label: 'Départements', link: '/territoires' },
        { label: collectivity.name },
      ]"
    />

    <div class="relative">
      <img
        v-if="collectivity.banner && collectivity.banner.large"
        :src="collectivity.banner.large"
        :alt="collectivity.title"
        class="absolute object-cover object-center w-full h-full"
      />

      <div
        :class="[
          'bg-primary',
          'absolute',
          'inset-0',
          { 'opacity-75': collectivity.banner },
        ]"
      />

      <div class="relative pt-1 pb-12 lg:py-12">
        <div class="container mx-auto px-4">
          <div class="py-8 text-center sm:text-left">
            <h1
              class="text-4xl max-w-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl"
            >
              <template v-if="collectivity.title">
                {{ collectivity.title }}
              </template>
              <template v-else>
                Bénévolat {{ collectivity.name }} | Rejoignez
                JeVeuxAider.gouv.fr
              </template>
            </h1>

            <p
              class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl"
            >
              <template v-if="collectivity.description">
                {{ collectivity.description }}
              </template>
              <template v-else>
                <b>{{ collectivity.name }}</b> • Votre organisation a besoin de
                renfort localement ? Vous souhaitez vous engager bénévolement au
                plus près de chez vous ? Rejoignez JeVeuxAider.gouv.fr dans
                votre département.
              </template>
            </p>

            <div
              class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
            >
              <div class="text-center">
                <p class="text-xs leading-6 font-medium text-white">
                  Je veux aider
                </p>
                <nuxt-link
                  v-if="!$store.getters.isLogged"
                  to="/register/volontaire"
                  class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white text-blue-800 hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
                >
                  Devenir réserviste
                </nuxt-link>
                <a
                  v-else
                  href="#search"
                  class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white text-blue-800 hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
                >
                  Trouver une mission
                </a>
              </div>
              <div class="mt-3 sm:mt-0 sm:ml-6 text-center">
                <p class="text-xs leading-6 font-medium text-white">
                  Mon organisation a besoin de renfort
                </p>
                <nuxt-link
                  :to="
                    $store.getters.isLogged &&
                    $store.getters.contextRole == 'responsable'
                      ? `/dashboard/structure/${$store.getters.structure.id}/missions/add`
                      : '/login'
                  "
                  class="shadow-lg w-full flex items-center justify-center px-8 py-3 border border-transparent border text-base leading-6 font-medium rounded-full text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-9"
                >
                  Proposer une mission
                </nuxt-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="py-20 bg-gray-50 overflow-hidden shadow-lg">
      <div class="relative container mx-auto px-6 sm:px-6 lg:px-8">
        <img
          class="hidden lg:block absolute top-0 right-0 transform -translate-x-3/4 translate-y-1 opacity-50"
          src="/images/france.svg"
          width="904"
          alt="France"
        />

        <div class="relative mx-auto my-8 px-4">
          <div class="mb-16">
            <h2
              class="text-center text-5xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
            >
              Engagez-vous près de chez vous
            </h2>
            <p
              class="mt-4 max-w-5xl mx-auto text-center text-xl leading-7 text-gray-500"
            >
              Avec JeVeuxAider.gouv.fr, soutenez de grandes causes dans votre
              territoire
            </p>
          </div>

          <div class="flex flex-wrap items-center justify-center">
            <div
              v-for="(city, key) in statistics.cities"
              :key="key"
              class="inline-flex mx-2 px-4 mb-6 py-2 rounded-full text-md font-semibold shadow-md tracking-wide uppercase bg-white text-gray-800 hover:bg-gray-50"
            >
              <nuxt-link
                :to="`/missions-benevolat?refinementList[type][0]=Mission en présentiel&aroundLatLng=${
                  city.coordonates
                }&place=${city.zipcode} ${
                  city.name
                }&refinementList[department_name][0]=${$options.filters.fullDepartmentFromValue(
                  collectivity.department
                )}`"
                >{{ city.name }}</nuxt-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="search" class="bg-primary">
      <div class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto text-center">
          <h2
            class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10"
          >
            <div>{{ collectivity.name }}</div>
            <span class="font-bold"
              >Trouvez une mission dans le département</span
            >
          </h2>
          <p class="text-xl leading-8 text-indigo-200 mt-2">
            <nuxt-link to="/regles-de-securite" target="_blank">
              Consulter les règles de sécurité
            </nuxt-link>
          </p>

          <dl
            class="mt-12 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
          >
            <div class="flex flex-col">
              <dd class="text-5xl leading-none font-bold text-white">
                {{ statistics.volontaires_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-white">Réservistes</dt>
            </div>
            <div class="flex flex-col mt-10 sm:mt-0">
              <dd class="text-5xl leading-none font-bold text-white">
                {{ statistics.structures_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-white">Organisations</dt>
            </div>

            <div class="flex flex-col mt-10 sm:mt-0">
              <dd class="text-5xl leading-none font-bold text-white">
                {{ statistics.participations_count | formatNumber }}
              </dd>
              <dt class="mt-2 text-lg font-medium text-white">
                Mises en relation
              </dt>
            </div>
          </dl>
        </div>
      </div>
    </div>

    <SearchMissions
      :facets="['domaines', 'format', 'template_title', 'structure.name']"
      :filters="`department:${collectivity.department}`"
    />
  </div>
</template>

<script>
export default {
  async asyncData({ $api, params }) {
    const collectivity = await $api.getCollectivity(params.slug)
    const { data: statistics } = await $api.getCollectivityStatistics(
      collectivity.id
    )
    return {
      collectivity,
      statistics,
    }
  },
  head() {
    return {
      title: `Devenez bénévole dans le département ${this.collectivity.name} | Je Veux Aider`,
      link: [
        {
          rel: 'canonical',
          href: `https://www.jeveuxaider.gouv.fr/territoires/departements/${this.collectivity.slug}`,
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            'Faites vivre la solidarité grâce au bénévolat dans votre département. Trouvez la mission qui vous correspond, dès 16 ans.',
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/jva-logo.png',
        },
      ],
    }
  },
}
</script>
