<template>
  <div>
    <el-dropdown v-if="canEditStatut" :size="size" split-button :type="type">
      <div style="min-width: 140px" class="text-left">
        <template v-if="loading"> Chargement... </template>
        <template v-else>
          {{ territoire.state | labelFromValue('territoires_states') }}
        </template>
      </div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item
          v-for="state in statesAvailable"
          :key="state.value"
          @click.native="onSubmitState(state.value)"
        >
          <template v-if="state.value == 'validated'"
            >Valider le territoire</template
          >
          <template v-if="state.value == 'refused'"
            >Refuser le territoire</template
          >
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
    <template v-else>
      <div class="text-sm">
        {{ territoire.state | labelFromValue('territoires_states') }}
      </div>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    territoire: {
      type: Object,
      required: true,
    },
    size: {
      type: String,
      required: false,
      default: 'small',
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.territoire },
      message: 'Êtes vous sur de vos changements ?',
    }
  },
  computed: {
    type() {
      if (this.territoire.state == 'waiting') {
        return 'warning'
      }
      return 'default'
    },
    canEditStatut() {
      return !!(
        ['waiting'].includes(this.territoire.state) &&
        this.$store.getters.contextRole === 'admin'
      )
    },
    statesAvailable() {
      return this.$store.getters.taxonomies.collectivities_states.terms.filter(
        (item) =>
          !['waiting'].includes(item.value) &&
          item.value != this.territoire.state
      )
    },
  },
  watch: {
    territoire(newValue) {
      this.form = { ...newValue }
    },
  },
  methods: {
    onSubmitState(state) {
      if (state == 'validated') {
        this.message = `Vous êtes sur le point de <b>valider</b> cette territoire.`
      }

      if (state == 'refused') {
        this.message = `Vous êtes sur le point de refuser ce territoire.`
      }

      this.$confirm(this.message, 'Changement de statut', {
        confirmButtonText: 'Je confirme',
        cancelButtonText: 'Annuler',
        dangerouslyUseHTMLString: true,
      })
        .then(() => {
          this.loading = true
          this.form.state = state
          this.$api
            .updateTerritoire(this.form.id, this.form)
            .then((response) => {
              this.$message.success({
                message: 'Le statut du territoire a été mis à jour',
              })
              this.$emit('updated', response.data)
              this.loading = false
            })
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>
