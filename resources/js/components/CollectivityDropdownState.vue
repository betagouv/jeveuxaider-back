<template>
  <div>
    <el-dropdown v-if="canEditStatut" :size="size" split-button :type="type">
      <div style="min-width: 140px" class="text-left">
        {{ form.state | labelFromValue('collectivities_states') }}
      </div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item
          v-for="state in statesAvailable"
          :key="state.value"
          @click.native="onSubmitState(state.value)"
        >
          <template v-if="state.value == 'validated'"
            >Valider la collectivité</template
          >
          <template v-if="state.value == 'refused'"
            >Refuser la collectivité</template
          >
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
    <template v-else>
      <div class="text-sm">
        {{ form.state | labelFromValue('collectivities_states') }}
      </div>
    </template>
  </div>
</template>

<script>
import { updateCollectivity } from '@/api/app'

export default {
  name: 'StructureDropdownState',
  props: {
    form: {
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
      message: 'Êtes vous sur de vos changements ?',
    }
  },
  computed: {
    type() {
      if (this.form.state == 'waiting') {
        return 'warning'
      }
      return 'default'
    },
    canEditStatut() {
      return ['waiting'].includes(this.form.state) &&
        this.$store.getters.contextRole === 'admin'
        ? true
        : false
    },
    statesAvailable() {
      return this.$store.getters.taxonomies.collectivities_states.terms.filter(
        (item) =>
          !['waiting'].includes(item.value) && item.value != this.form.state
      )
    },
  },
  methods: {
    onSubmitState(state) {
      if (state == 'validated') {
        this.message = `Vous êtes sur le point de <b>valider</b> cette collectivité.`
      }

      if (state == 'refused') {
        this.message = `Vous êtes sur le point de refuser cette collectivité.`
      }

      this.$confirm(this.message, 'Changement de statut', {
        confirmButtonText: 'Je confirme',
        cancelButtonText: 'Annuler',
        dangerouslyUseHTMLString: true,
      })
        .then(() => {
          this.form.state = state
          updateCollectivity(this.form.id, this.form)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Le statut de la collectivité a été mis à jour',
              })
              this.$emit('updated', response.data)
            })
            .catch((error) => {
              this.errors = error.response.data.errors
            })
        })
        .catch(() => {})
    },
  },
}
</script>
