<template>
  <div class="bg-gray-100">
    <Breadcrumb
      :items="[{ label: 'Territoires engagés', link: '/territoires' }]"
    />

    <div class="py-8 md:py-12 lg:py-20 bg-gray-50 overflow-hidden shadow-lg">
      <div class="relative max-w-xl mx-auto lg:max-w-[1280px]">
        <div class="px-4">
          <img
            class="hidden lg:block absolute transform translate-y-1 opacity-50"
            style="left: 100%; transform: translateX(-75%)"
            src="/images/france.svg"
            width="904"
            alt=""
          />

          <div class="relative">
            <h1
              class="text-center text-3xl md:text-5xl lg:text-6xl leading-none font-bold tracking-tight text-gray-900"
            >
              Rejoignez JeVeuxAider.gouv.fr
              <br />
              dans votre territoire
            </h1>

            <p
              class="mt-4 text-center max-w-2xl text-xl leading-7 text-gray-500 lg:mx-auto"
            >
              Sur l'ensemble du territoire français, des milliers de
              réservistes, de structures et d'association ont déjà rejoint
              <b>JeVeuxAider.gouv.fr</b>.
            </p>
          </div>

          <!-- Search bar -->
          <div class="flex-1 flex justify-between lg:mx-auto mt-10">
            <div class="flex-1 flex bg-white z-10 shadow-md rounded-lg">
              <form class="w-full flex md:ml-0 mb-0" action="#" method="GET">
                <label for="search_field" class="sr-only">Recherche</label>
                <div
                  class="relative w-full text-cool-gray-400 focus-within:text-cool-gray-600"
                >
                  <div
                    class="absolute inset-y-0 left-0 flex items-center pointer-events-none"
                  >
                    <svg
                      class="h-5 w-5 ml-3 sm:ml-6"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      />
                    </svg>
                  </div>

                  <input
                    id="search_field"
                    v-model="query"
                    class="block w-full h-full pl-10 pr-4 py-4 sm:px-16 sm:py-6 rounded-md text-cool-gray-900 placeholder-cool-gray-500 focus:placeholder-cool-gray-400 !outline-none focus:ring transition truncate text-md sm:text-lg md:text-xl"
                    placeholder="Trouvez votre ville ou département"
                    type="search"
                  />
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Tabs -->
        <div class="relative my-8 sm:px-4">
          <nav
            class="flex overflow-scroll sm:overflow-visible pt-1 pb-3 sm:py-0 sm:justify-center sm:gap-4"
          >
            <span
              v-for="(type, index) in types"
              :key="type.slug"
              tabindex="0"
              :class="[
                {
                  'text-white bg-[#070191] focus:text-white focus:bg-[#070191]':
                    activeType == type.slug,
                },
                {
                  'text-gray-500 hover:text-[#070191] bg-white':
                    activeType != type.slug,
                },
                {
                  'mr-4 sm:mr-0':
                    types[index].slug == types[types.length - 1].slug,
                },
              ]"
              class="px-3 text-center lg:px-5 py-3 lg:py-4 shadow cursor-pointer font-medium text-md lg:text-xl leading-6 rounded-md !outline-none focus:ring transition sm:w-full lg:w-auto flex-none sm:flex-initial ml-4 sm:ml-0 !outline-none"
              @click="activeType = type.slug"
            >
              {{ type.label }} ({{ typeCount(type.slug) }})
            </span>
          </nav>
        </div>

        <!-- Tab content -->
        <div class="relative px-4">
          <div class="mx-auto">
            <div v-for="(group, index) in groups[activeType]" :key="index">
              <div
                v-if="territoriesByGroup(group).length > 0"
                class="mt-10 text-xl font-bold"
              >
                <span>{{ group[0] }}</span>
                <span v-if="group.length > 1">
                  - {{ group[group.length - 1] }}
                </span>

                <div
                  class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4"
                >
                  <nuxt-link
                    v-for="territory in territoriesByGroup(group)"
                    :key="territory.id"
                    :to="territory.full_url"
                    class="col-span-1 flex justify-center items-center text-center px-4 py-2 bg-white shadow-md rounded-lg border-[#070191] border-b-2 text-[#242526] hover:shadow-lg hover:text-gray-900 !outline-none focus:ring transition"
                    style="min-height: 80px"
                  >
                    <span class="font-semibold">{{ territory.name }}</span>
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  async asyncData({ $api }) {
    const { data: cities } = await $api.fetchTerritoires({
      'filter[state]': 'validated',
      'filter[is_published]': true,
      'filter[type]': 'city',
      append: 'full_url',
      pagination: 9999,
    })

    const { data: departments } = await $api.fetchTerritoires({
      'filter[state]': 'validated',
      'filter[is_published]': true,
      'filter[type]': 'department',
      append: ' full_url',
      pagination: 999,
    })

    return {
      cities: cities.data,
      departments: departments.data,
    }
  },
  data() {
    const types = [
      {
        slug: 'cities',
        label: 'Villes',
      },
      {
        slug: 'departments',
        label: 'Départements',
      },
    ]

    return {
      query: '',
      types,
      activeType: types[0].slug,
      groups: {
        cities: [
          ['A', 'B', 'C', 'D', 'E'],
          ['F', 'G', 'H', 'I', 'J'],
          ['K', 'L', 'M', 'N', 'O'],
          ['P', 'Q', 'R', 'S', 'T'],
          ['U', 'V', 'W', 'X', 'Y', 'Z'],
        ],
        departments: [
          ['A'],
          ['B', 'C'],
          ['D', 'E', 'F'],
          ['G', 'H'],
          ['I'],
          ['J', 'L'],
          ['M', 'N'],
          ['O', 'P'],
          ['R', 'S', 'T'],
          ['V', 'Y'],
        ],
      },
    }
  },
  head() {
    return {
      title:
        'Inscrivez votre territoire sur JeVeuxAider.gouv.fr, la plateforme publique du bénévolat proposée par la Réserve Civique',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/territoires',
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            "Partout en France, des milliers de bénévoles, d'associations et de communes se mobilisent sur JeVeuxAider.gouv.fr. Rejoignez la plateforme et faites vivre la solidarité locale. ",
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
      ],
    }
  },
  methods: {
    typeCount(type) {
      return this[type].filter((item) =>
        this.slugify(item.name).includes(this.slugify(this.query))
      ).length
    },
    territoriesByGroup(group) {
      return this[this.activeType]
        .filter(
          (territory) =>
            group.includes(territory.name[0]) &&
            this.slugify(territory.name).includes(this.slugify(this.query))
        )
        .sort((a, b) => (a.name < b.name ? -1 : 1))
    },
    slugify(str) {
      const map = {
        '-': ' ',
        a: 'á|à|ã|â|À|Á|Ã|Â',
        e: 'é|è|ê|É|È|Ê',
        i: 'í|ì|î|Í|Ì|Î',
        o: 'ó|ò|ô|õ|Ó|Ò|Ô|Õ',
        u: 'ú|ù|û|ü|Ú|Ù|Û|Ü',
        c: 'ç|Ç',
        n: 'ñ|Ñ',
      }
      for (const pattern in map) {
        str = str.replace(new RegExp(map[pattern], 'g'), pattern)
      }
      return str.toLowerCase()
    },
  },
}
</script>

<style lang="postcss" scoped>
input::placeholder {
  font-weight: 400 !important;
}
</style>
