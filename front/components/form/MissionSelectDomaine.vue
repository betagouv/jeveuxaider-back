<template>
  <div class="max-w-3xl">
    <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
      <i class="el-icon-info text-primary mt-1 mr-2"></i>
      <div class="flex-1">
        JeVeuxAider.gouv.fr vous permet de mobiliser des bénévoles dans dix
        domaines d'action. Choisissez le domaine d'action principal pour
        faciliter la recherche de mission.
      </div>
    </div>
    <div class="flex flex-wrap">
      <div
        v-for="domaine in domaines"
        :key="domaine.id"
        class="
          flex flex-col
          bg-gray-100
          p-6
          m-2
          text-center
          items-center
          rounded-md
          w-56
        "
      >
        <div
          class="
            p-2
            flex
            items-center
            justify-center
            bg-primary
            h-14
            w-14
            rounded-md
            text-white
            mb-4
          "
        >
          <img v-if="domaine.image" :src="domaine.image" />
        </div>
        <div class="mb-4">{{ domaine.name.fr }}</div>
        <el-button
          plain
          type="primary"
          size="medium"
          class="mt-auto"
          @click="$emit('selected', domaine.id)"
        >
          Choisir
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    domaines: {
      type: Array,
      required: true,
    },
  },
  methods: {},
}
</script>
