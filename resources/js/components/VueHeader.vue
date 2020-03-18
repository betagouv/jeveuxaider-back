<template>
  <header>
    <div class="bg-blue-900">
      <div class="container mx-auto px-4">
        <div
          class="flex flex-wrap items-center justify-center md:h-16 border-b border-blue-800"
        >
          <div class="flex-shrink-0 my-4 md:my-0">
            <a href="/">
              <img
                class="h-6"
                src="/images/logo-reserve-civique_light.svg"
                alt="Réserve Civique"
              />
            </a>
          </div>

          <div class="mb-3 md:ml-auto md:mb-0 w-full md:w-auto">
            <div class="flex flex-wrap items-center justify-center -m-2">
              <a
                href="/missions"
                :class="{
                  'bg-blue-700': isCurrentPath('/missions')
                }"
                class="m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition focus:bg-gray-700 hover:bg-blue-700"
                >Trouver une mission</a
              >

              <a
                href="/user/missions"
                :class="{
                  'bg-blue-700': isCurrentPath('/user/missions')
                }"
                class=" m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
                >Mes missions</a
              >
              <a
                href
                class=" m-2 px-3 py-2 rounded-md text-sm font-medium text-white transition hover:text-white hover:bg-blue-700"
                :class="{
                  'bg-blue-700': isCurrentPath('/regles-de-securite')
                }"
                >Règles de sécurité</a
              >
            </div>
          </div>
          <div class="ml-3">
            <dropdown-user></dropdown-user>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import DropdownUser from "@/components/DropdownUser.vue"

export default {
  name: "VueHeader",
  components: {
    DropdownUser,
  },
  props: {
    title: String,
    required: true
  },
  data() {
    return {
      open: false
    };
  },
  methods: {
    isCurrentPath(path) {
      return window.location.pathname === path;
    }
  }
};
</script>
