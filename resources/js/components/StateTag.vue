<template>
  <div class="state-tag">
    <el-tag v-if="state == 'Brouillon'" type="info" :size="size" class="m-1">
      Brouillon
    </el-tag>
    <el-tag
      v-if="state == 'En attente de validation'"
      type="warning"
      :size="size"
      class="m-1"
    >
      En attente de validation
    </el-tag>
    <el-tag
      v-if="state == 'En attente de correction'"
      type="warning"
      :size="size"
      class="m-1"
    >
      En attente de correction
    </el-tag>
    <el-tag v-if="state == 'Validée'" type="success" :size="size" class="m-1">
      Validée
    </el-tag>
    <el-tag v-if="state == 'Refusée'" type="danger" :size="size" class="m-1">
      Refusée
    </el-tag>
    <el-tag v-if="state == 'Signalée'" type="danger" :size="size" class="m-1">
      Signalée
    </el-tag>
    <el-tag v-if="state == 'Annulée'" type="info" :size="size" class="m-1">
      Annulée
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
      v-if="state == 'Mission validée'"
      type="success"
      :size="size"
      class="m-1"
    >
      Mission validée
    </el-tag>
    <el-tag
      v-if="state == 'Mission proposée'"
      type="success"
      :size="size"
      class="m-1"
    >
      Mission proposée
    </el-tag>
    <el-tag
      v-if="state == 'Mission refusée'"
      type="info"
      :size="size"
      class="m-1"
    >
      Mission refusée
    </el-tag>
    <el-tag
      v-if="state == 'Mission annulée'"
      type="info"
      :size="size"
      class="m-1"
    >
      Mission annulée
    </el-tag>
    <el-tag
      v-if="state == 'Mission en cours'"
      type="success"
      :size="size"
      class="m-1"
    >
      Mission en cours
    </el-tag>
    <el-tag
      v-if="state == 'Abandon de mission'"
      type="danger"
      :size="size"
      class="m-1"
    >
      Abandon de mission
    </el-tag>
    <el-tag
      v-if="state == 'Exclusion de la mission'"
      type="danger"
      :size="size"
      class="m-1"
    >
      Exclusion de la mission
    </el-tag>
    <el-tag
      v-if="state == 'Arrêt de la mission'"
      type="info"
      :size="size"
      class="m-1"
    >
      Arrêt de la mission
    </el-tag>
    <el-tag
      v-if="state == 'Mission effectuée'"
      type=""
      :size="size"
      class="m-1"
    >
      Mission effectuée
    </el-tag>
  </div>
</template>

<script>
export default {
  name: "StateTag",
  props: {
    state: {
      type: String,
      required: true
    },
    size: {
      type: String,
      required: false,
      default: "medium"
    }
  },
  data() {
    return {};
  },
  methods: {}
};
</script>
