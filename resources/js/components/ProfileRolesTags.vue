<template>
  <div class="roles-tags">
    <el-tag v-if="profile.roles.admin" type="danger" :size="size" class="m-1">
      Admin
    </el-tag>
    <el-tag v-if="profile.volontaire" type="info" :size="size" class="m-1">
      Volontaire
    </el-tag>
    <el-tooltip
      v-if="profile.roles.referent"
      class="item"
      effect="dark"
      :content="profile.referent_department | fullDepartmentFromValue"
      placement="top"
    >
      <el-tag type="warning" :size="size" class="m-1">
        Référent
      </el-tag>
    </el-tooltip>
    <el-tooltip
      v-if="profile.roles.referent_regional"
      class="item"
      effect="dark"
      :content="profile.referent_region"
      placement="top"
    >
      <el-tag type="warning" :size="size" class="m-1">
        Régional
      </el-tag>
    </el-tooltip>
    <el-tooltip
      v-if="profile.roles.superviseur"
      class="item"
      effect="dark"
      :content="profile.reseau.name"
      placement="top"
    >
      <el-tag type="" :size="size" class="m-1">
        Superviseur
      </el-tag>
    </el-tooltip>
    <el-tag v-if="profile.roles.analyste" type="" :size="size" class="m-1">
      Analyste
    </el-tag>
    <el-tooltip
      v-if="profile.roles.responsable"
      class="item"
      effect="dark"
      :content="structure_as_responsable.name"
      placement="top"
    >
      <el-tag type="info" :size="size" class="m-1">
        Responsable
      </el-tag>
    </el-tooltip>
    <el-tooltip
      v-if="profile.roles.tuteur"
      class="item"
      effect="dark"
      :content="structures_as_tuteur.name"
      placement="top"
    >
      <el-tag type="info" :size="size" class="m-1">
        Tuteur
      </el-tag>
    </el-tooltip>
    <el-tooltip
      v-if="!profile.has_user"
      class="item"
      effect="dark"
      content="Ce profil n'a pas créé son compte"
      placement="top"
    >
      <el-tag type="info" :size="size" class="m-1">
        <i class="el-icon-info" /> Invité
      </el-tag>
    </el-tooltip>
  </div>
</template>

<script>
export default {
  name: '',
  props: {
    profile: {
      type: Object,
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
  computed: {
    structure_as_responsable() {
      return this.profile.structures.filter(
        (structure) => structure.pivot.role == 'responsable'
      )[0]
    },
    structures_as_tuteur() {
      return this.profile.structures.filter(
        (structure) => structure.pivot.role == 'tuteur'
      )[0]
    },
  },
  methods: {},
}
</script>
