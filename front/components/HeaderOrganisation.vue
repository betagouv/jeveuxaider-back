<template>
  <div
    class="header px-12 flex"
    :class="{ 'mb-8': $store.getters.contextRole == 'responsable' }"
  >
    <div class="header-titles flex-1">
      <div class="text-m text-gray-600 uppercase">Organisation</div>
      <div class="flex items-center flex-wrap">
        <div class="font-bold text-2-5xl text-gray-800 mr-2">
          {{ structure.name }}
        </div>
        <TagModelState v-if="structure.state" :state="structure.state" />
        <el-tag
          v-if="structure.is_reseau"
          size="medium"
          class="m-1 ml-0"
          type="danger"
        >
          Tête de réseau
        </el-tag>
        <el-tag v-if="structure.reseau_id" class="m-1 ml-0" size="medium">
          {{ structure.reseau_id | reseauFromValue }}
        </el-tag>
      </div>
      <div
        v-if="structure.statut_juridique == 'Association'"
        class="font-light text-gray-600 flex items-center"
      >
        <div class="mr-2">Votre page vitrine :</div>
        <div
          :class="structure.state == 'Validée' ? 'bg-green-500' : 'bg-red-500'"
          class="rounded-full h-2 w-2 mr-2"
        ></div>
        <nuxt-link
          v-if="structure.state == 'Validée'"
          :to="structure.full_url"
          target="_blank"
          class="underline hover:no-underline"
        >
          {{ $config.appUrl }}{{ structure.full_url }}
        </nuxt-link>
        <span v-else class="cursor-default">
          {{ $config.appUrl }}{{ structure.full_url }}
        </span>
      </div>
    </div>
    <slot name="action" />
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
