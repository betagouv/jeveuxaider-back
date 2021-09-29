<template>
  <div
    class="header px-12"
    :class="{ 'mb-8': $store.getters.contextRole == 'responsable' }"
  >
    <div class="flex items-start">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex items-center flex-wrap">
          <div class="font-bold text-[1.75rem] text-[#242526] mr-2">
            {{ structure.name }}
          </div>
          <TagModelState v-if="structure.state" :state="structure.state" />
        </div>
      </div>

      <slot name="action" />
    </div>

    <div
      v-if="structure.statut_juridique == 'Association'"
      class="font-light text-gray-600 flex items-center"
    >
      <div class="mr-2 flex-none">Votre page vitrine :</div>
      <div
        :class="structure.state == 'Validée' ? 'bg-[#0e9f6e]' : 'bg-[#f56565]'"
        class="rounded-full h-2 w-2 mr-2 flex-none"
      ></div>
      <nuxt-link
        v-if="structure.state == 'Validée'"
        :to="structure.full_url"
        target="_blank"
        class="underline hover:no-underline truncate"
      >
        {{ $config.appUrl }}{{ structure.full_url }}
      </nuxt-link>
      <span v-else class="cursor-default truncate">
        {{ $config.appUrl }}{{ structure.full_url }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    structure: {
      type: Object,
      required: true,
    },
  },
}
</script>
