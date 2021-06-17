<template>
  <div class="px-12 max-w-4xl flex flex-col space-y-8">
    <div v-if="$store.getters.user.profile.structures.length">
      <div class="font-semibold text-md uppercase text-gray-800 mb-4">
        Organisations
        <span>({{ $store.getters.user.profile.structures.length }})</span>
      </div>
      <div
        class="bg-white border border-gray-200 overflow-hidden sm:rounded-md"
      >
        <ul class="divide-y divide-gray-200">
          <li
            v-for="structure in $store.getters.user.profile.structures"
            :key="structure.id"
          >
            {{ structure.name }}
          </li>
        </ul>
      </div>
    </div>
    <div v-if="$store.getters.user.profile.territoires.length">
      <div class="font-semibold text-lg uppercase text-gray-800 mb-4">
        Territoires
        <span>({{ $store.getters.user.profile.territoires.length }})</span>
      </div>
      <div
        class="bg-white border border-gray-200 overflow-hidden sm:rounded-md"
      >
        <ul class="divide-y divide-gray-200">
          <li
            v-for="territoire in $store.getters.user.profile.territoires"
            :key="territoire.id"
          >
            {{ territoire.name }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>
