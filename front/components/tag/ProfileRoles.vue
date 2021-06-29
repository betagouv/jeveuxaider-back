<template>
  <div class="roles-tags flex items-center flex-wrap">
    <el-tag v-if="profile.roles.admin" type="danger" :size="size" class="m-1">
      Admin
    </el-tag>

    <el-tag v-if="profile.volontaire" type="info" :size="size" class="m-1">
      Bénévole
    </el-tag>

    <el-tag
      v-if="profile.roles.referent"
      v-tooltip="{
        content: $options.filters.fullDepartmentFromValue(
          profile.referent_department
        ),
        classes: 'bo-style',
      }"
      type="warning"
      :size="size"
      class="m-1"
    >
      Référent
    </el-tag>

    <el-tag
      v-if="profile.roles.referent_regional"
      v-tooltip="{
        content: profile.referent_region,
        classes: 'bo-style',
      }"
      type="warning"
      :size="size"
      class="m-1"
    >
      Régional
    </el-tag>

    <el-tag
      v-if="profile.roles.superviseur"
      v-tooltip="{
        content: profile.reseau.name,
        classes: 'bo-style',
      }"
      type=""
      :size="size"
      class="m-1"
    >
      Superviseur
    </el-tag>

    <el-tag v-if="profile.roles.analyste" type="" :size="size" class="m-1">
      Analyste
    </el-tag>

    <template v-if="profile.roles.responsable">
      <template v-if="profile.structures.length > 0">
        <el-tag
          v-for="structure in profile.structures"
          :key="structure.id"
          v-tooltip="{
            content: structure.name,
            classes: 'bo-style',
          }"
          type="info"
          :size="size"
          class="m-1"
        >
          Responsable
        </el-tag>
      </template>
      <template v-if="profile.territoires">
        <el-tag
          v-for="territoire in profile.territoires"
          :key="territoire.id"
          v-tooltip="{
            content: territoire.name,
            classes: 'bo-style',
          }"
          type="info"
          :size="size"
          class="m-1"
        >
          Responsable
        </el-tag>
      </template>
    </template>

    <el-tag
      v-if="profile.is_visible"
      v-tooltip="{
        content: 'Ce profil est visible dans la recherche',
        classes: 'bo-style',
      }"
      type=""
      :size="size"
      class="m-1"
    >
      <i class="el-icon-search" /> Visible
    </el-tag>

    <el-tag
      v-if="profile.domaines.length > 0"
      type="info"
      :size="size"
      class="m-1"
    >
      {{ profile.domaines.length }}
      {{ profile.domaines.length | pluralize(['domaine', 'domaines']) }}
    </el-tag>

    <el-tag
      v-if="profile.skills.length > 0"
      type="info"
      :size="size"
      class="m-1"
    >
      {{ profile.skills.length }}
      {{ profile.skills.length | pluralize(['compétence', 'compétences']) }}
    </el-tag>
  </div>
</template>

<script>
export default {
  props: {
    profile: {
      type: Object,
      required: true,
    },
    size: {
      type: String,
      required: false,
      default: 'big',
    },
  },
  data() {
    return {}
  },
  computed: {},
  methods: {},
}
</script>
