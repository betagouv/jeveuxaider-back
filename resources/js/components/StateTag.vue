<template>
  <div class="state-tag">
    <el-tag v-if="state == 'Brouillon'" type="info" :size="size" class="m-1">
      Brouillon
    </el-tag>
    <el-tag
      v-if="['En attente de validation', 'waiting'].includes(state)"
      type="warning"
      :size="size"
      class="m-1"
    >
      En attente de validation
    </el-tag>
    <el-tag v-if="state == 'Terminée'" type="info" :size="size" class="m-1">
      Terminée
    </el-tag>
    <el-tag v-if="state == 'Archivée'" type="info" :size="size" class="m-1">
      Archivée
    </el-tag>
    <el-tag
      v-if="state == 'En attente de mission'"
      type="warning"
      :size="size"
      class="m-1"
    >
      En attente de mission
    </el-tag>
    <el-tag
      v-if="['Validée', 'validated'].includes(state)"
      type="success"
      :size="size"
      class="m-1"
    >
      Validée
    </el-tag>
    <el-tag
      v-if="['Refusée', 'refused'].includes(state)"
      type="info"
      :size="size"
      class="m-1"
    >
      Refusée
    </el-tag>
    <el-tag v-if="state == 'Annulée'" type="info" :size="size" class="m-1">
      Annulée
    </el-tag>
    <el-tag v-if="state == 'Effectuée'" type="" :size="size" class="m-1">
      Effectuée
    </el-tag>
    <el-tag v-if="state == 'Signalée'" type="danger" :size="size" class="m-1">
      Signalée
    </el-tag>
    <el-tag
      v-if="state == 'Désinscrite'"
      type="danger"
      :size="size"
      class="m-1"
    >
      Désinscrite
    </el-tag>
  </div>
</template>

<script>
export default {
  name: 'StateTag',
  props: {
    state: {
      type: String,
      required: true,
    },
    size: {
      type: String,
      required: false,
      default: 'medium',
    },
  },
  data() {
    return {}
  },
  methods: {},
}
</script>
