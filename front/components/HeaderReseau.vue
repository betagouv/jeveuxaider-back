<template>
  <div
    class="header px-12"
    :class="{ 'mb-8': $store.getters.contextRole == 'tete_de_reseau' }"
  >
    <div class="flex items-start">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Réseau</div>
        <div class="flex items-center flex-wrap">
          <div class="font-bold text-[1.75rem] text-[#242526] mr-2">
            {{ reseau.name }}
          </div>
          <!-- <TagModelState v-if="structure.state" :state="structure.state" /> -->
        </div>
      </div>

      <slot name="action">
        <template v-if="$store.getters.contextRole == 'admin'">
          <DropdownReseauButton :reseau="reseau" />
        </template>
        <template v-else
          ><nuxt-link :to="`/dashboard/reseaux/${reseau.id}/structures/invite`">
            <el-button type="primary">Inviter une antenne</el-button>
          </nuxt-link>
        </template>
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    reseau: {
      type: Object,
      required: true,
    },
  },
}
</script>
