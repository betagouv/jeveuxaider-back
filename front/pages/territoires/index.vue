<template>
  <div class="bg-gray-100">
    <breadcrumb
      :items="[
        { label: 'Départements et collectivités', link: '/territoires' },
      ]"
    />

    <div class="py-8 md:py-20 bg-gray-50 overflow-hidden shadow-lg">
      <div
        class="relative max-w-xl mx-auto px-6 sm:px-6 lg:px-8 lg:max-w-screen-xl"
      >
        <img
          class="hidden lg:block absolute transform translate-y-1 opacity-50"
          style="left: 100%; transform: translateX(-75%) !important"
          src="/images/france.svg"
          width="904"
          alt=""
        />

        <div class="relative">
          <h3
            class="text-center text-3xl md:text-6xl leading-none font-bold tracking-tight text-gray-900"
          >
            Rejoignez JeVeuxAider.gouv.fr
            <br />
            dans votre territoire
          </h3>
          <p
            class="mt-4 text-center max-w-2xl text-xl leading-7 text-gray-500 lg:mx-auto"
          >
            Sur l'ensemble du territoire français, des milliers de réservistes,
            de structures et d'association ont déjà rejoint la
            <b>JeVeuxAider.gouv.fr</b>.
          </p>
        </div>

        <!-- Search bar -->
        <div class="flex-1 flex justify-between lg:mx-auto mt-10">
          <div class="flex-1 flex bg-white z-10 px-8 shadow-md rounded-lg">
            <form class="w-full flex md:ml-0 mb-0" action="#" method="GET">
              <label for="search_field" class="sr-only">Recherche</label>
              <div
                class="relative w-full text-cool-gray-400 focus-within:text-cool-gray-600"
              >
                <div
                  class="absolute inset-y-0 left-0 flex items-center pointer-events-none"
                >
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    ></path>
                  </svg>
                </div>
                <input
                  id="search_field"
                  v-model="query"
                  class="block w-full text-xl h-full pl-8 pr-6 py-6 rounded-md text-cool-gray-900 placeholder-cool-gray-500 focus:outline-none focus:placeholder-cool-gray-400"
                  placeholder="Trouvez votre département ou votre collectivité"
                  type="search"
                />
              </div>
            </form>
          </div>
        </div>

        <div class="relative my-8">
          <nav class="flex justify-center">
            <span
              :class="
                tab == 'departments'
                  ? 'text-white bg-blue-800 focus:text-white focus:bg-blue-800'
                  : 'text-gray-500 hover:text-blue-800 bg-white'
              "
              class="px-3 text-center lg:px-5 py-3 lg:py-4 shadow cursor-pointer font-medium text-md lg:text-xl leading-6 rounded-md focus:outline-none"
              @click="tab = 'departments'"
            >
              Départements ({{ departmentsCount }})
            </span>
            <span
              :class="
                tab == 'collectivities'
                  ? 'text-white bg-blue-800 focus:text-white focus:bg-blue-800'
                  : 'text-gray-500 hover:text-blue-800 bg-white'
              "
              class="ml-4 px-3 text-center lg:px-5 py-3 lg:py-4 shadow cursor-pointer font-medium text-md lg:text-xl leading-6 rounded-md focus:outline-none"
              @click="tab = 'collectivities'"
            >
              Collectivités ({{ collectivitiesCount }})
            </span>
          </nav>
        </div>

        <div class="relative">
          <div v-if="tab == 'departments'" class="mx-auto">
            <div
              v-for="group in departmentGroups"
              :key="group"
              class="mt-10 text-xl font-bold pl-2"
            >
              <div v-if="departmentsFilteredByLetters(group).length > 0">
                {{ group }}
                <div
                  class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-4 lg:mt-2"
                >
                  <nuxt-link
                    v-for="department in departments.filter(
                      (department) =>
                        department.group == group &&
                        slugify(department.name).includes(slugify(query))
                    )"
                    :key="department.name"
                    :to="department.url"
                  >
                    <div
                      class="col-span-1 flex justify-center items-center text-center px-4 py-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:shadow-lg hover:text-gray-900"
                    >
                      <span class="font-semibold">{{ department.name }}</span>
                    </div>
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>
          <div v-if="tab == 'collectivities'" class="mx-auto">
            <div
              v-for="(group, i) in collectivityGroups"
              :key="i"
              class="mt-10 text-xl font-bold pl-2"
            >
              <div v-if="collectivitiesFilteredByLetters(group).length > 0">
                {{ group[0] }} - {{ group[group.length - 1] }}
                <div
                  class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-4 lg:mt-2"
                >
                  <nuxt-link
                    v-for="collectivity in collectivitiesFilteredByLetters(
                      group
                    )"
                    :key="collectivity.id"
                    :to="`territoires/collectivites/${collectivity.slug}`"
                  >
                    <div
                      class="col-span-1 flex justify-center items-center text-center px-4 py-6 bg-white shadow-md rounded-lg border-blue-800 border-b-2 text-gray-800 hover:border hover:shadow-lg hover:text-gray-900"
                    >
                      <span class="font-semibold">{{ collectivity.name }}</span>
                    </div>
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
import departments from '@/assets/json/departments.json'

export default {
  data() {
    return {
      query: '',
      tab: 'departments',
      departments,
      collectivities: [],
      collectivityGroups: [
        ['A', 'B', 'C', 'D', 'E'],
        ['F', 'G', 'H', 'I', 'J'],
        ['K', 'L', 'M', 'N', 'O'],
        ['P', 'Q', 'R', 'S', 'T'],
        ['U', 'V', 'W', 'X', 'Y', 'Z'],
      ],
      departmentGroups: [
        'A',
        'B - C',
        'D - E - F',
        'G - H',
        'I',
        'J - L',
        'M - N',
        'O - P',
        'R - S - T',
        'V - Y',
      ],
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchAllCollectivities({
      'filter[state]': 'validated',
      'filter[published]': true,
      pagination: 999,
    })

    this.collectivities = data.data
  },
  computed: {
    collectivitiesCount() {
      return this.collectivities.filter((item) =>
        this.slugify(item.name).includes(this.slugify(this.query))
      ).length
    },
    departmentsCount() {
      return this.departments.filter((item) =>
        this.slugify(item.name).includes(this.slugify(this.query))
      ).length
    },
  },
  methods: {
    collectivitiesFilteredByLetters(letters) {
      return this.collectivities.filter(
        (item) =>
          letters.includes(item.name[0]) &&
          this.slugify(item.name).includes(this.slugify(this.query))
      )
    },
    departmentsFilteredByLetters(letters) {
      return this.departments.filter(
        (department) =>
          department.group == letters &&
          this.slugify(department.name).includes(this.slugify(this.query))
      )
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

<style scoped>
input::placeholder {
  font-weight: 400 !important;
}
</style>
