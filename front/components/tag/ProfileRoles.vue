<template>
  <div class="roles-tags flex items-center flex-wrap">
    <el-tag v-if="profile.roles.admin" type="danger" :size="size" class="m-1">
      Admin
    </el-tag>
    <el-tag v-if="profile.volontaire" type="info" :size="size" class="m-1">
      Bénévole
    </el-tag>
    <el-tooltip
      v-if="profile.roles.referent"
      class="item"
      effect="dark"
      :content="profile.referent_department | fullDepartmentFromValue"
      placement="top"
    >
      <el-tag type="warning" :size="size" class="m-1"> Référent </el-tag>
    </el-tooltip>
    <el-tooltip
      v-if="profile.roles.referent_regional"
      class="item"
      effect="dark"
      :content="profile.referent_region"
      placement="top"
    >
      <el-tag type="warning" :size="size" class="m-1"> Régional </el-tag>
    </el-tooltip>
    <el-tooltip
      v-if="profile.roles.superviseur"
      class="item"
      effect="dark"
      :content="profile.reseau.name"
      placement="top"
    >
      <el-tag type="" :size="size" class="m-1"> Superviseur </el-tag>
    </el-tooltip>
    <el-tag v-if="profile.roles.analyste" type="" :size="size" class="m-1">
      Analyste
    </el-tag>
    <el-tooltip
      v-if="profile.roles.responsable"
      class="item"
      effect="dark"
      :content="structure.name"
      placement="top"
    >
      <el-tag type="info" :size="size" class="m-1"> Responsable </el-tag>
    </el-tooltip>
    <el-tooltip
      v-if="profile.is_visible"
      class="item"
      effect="dark"
      content="Ce profil est visible dans la recherche"
      placement="top"
    >
      <el-tag type="" :size="size" class="m-1">
        <i class="el-icon-search" /> Visible
      </el-tag>
    </el-tooltip>

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
  computed: {
    structure() {
      return this.profile.structures.filter(
        (structure) => structure.pivot.role == 'responsable'
      )[0]
    },
  },
  methods: {},
}
</script>
