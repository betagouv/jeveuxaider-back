<template>
  <div>
    <el-dropdown v-if="canEditStatut" size="small" split-button :type="type">
      <div style="min-width: 140px; text-align: left">{{ form.state }}</div>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item
          v-for="state in statesAvailable"
          :key="state.value"
          @click.native="onSubmitState(state.value)"
        >
          <template v-if="state.value == 'En attente de validation'"
            >Repasser en validation</template
          >
          <template v-if="state.value == 'Validée'"
            >Valider la participation</template
          >
          <template v-if="state.value == 'Refusée'"
            >Refuser la participation</template
          >
          <template v-if="state.value == 'Annulée'"
            >Annuler la participation</template
          >
          <template v-if="state.value == 'Effectuée'"
            >Terminer la mission</template
          >
        </el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
    <template v-else>
      <div class="text-sm">{{ form.state }}</div>
    </template>
    <el-dialog
      :close-on-click-modal="false"
      title="Décliner la participation"
      width="100%"
      :visible.sync="dialogVisible"
      style="max-width: 600px; margin: auto"
    >
      <div class="mb-2">
        Vous êtes sur le point de décliner la participation de
        {{ form.profile.full_name }}.
      </div>
      <el-form
        ref="messageForm"
        :model="messageForm"
        :rules="rules"
        class="mt-4"
        :hide-required-asterisk="true"
      >
        <el-form-item label="Raison" prop="reason">
          <el-radio-group v-model="messageForm.reason">
            <el-radio
              v-for="item in $store.getters.taxonomies
                .participation_declined_reasons.terms"
              :key="item.value"
              :label="item.value"
              class="w-full mb-2"
              >{{ item.label }}</el-radio
            >
          </el-radio-group>
        </el-form-item>
        <el-form-item label="Votre message" prop="content">
          <el-input
            v-model="messageForm.content"
            placeholder="Plus d'explications si nécéssaire"
            :autosize="{ minRows: 3, maxRows: 8 }"
            type="textarea"
            :autofocus="true"
            autocomplete="off"
          ></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false"> Annuler </el-button>
        <el-button
          :loading="dialogLoading"
          type="primary"
          @click="handleDeclineSubmit"
          >Décliner la participation</el-button
        >
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { updateParticipation } from '@/api/participation'

export default {
  name: 'ParticipationDropdownState',
  props: {
    form: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      dialogLoading: false,
      dialogVisible: false,
      messageForm: {},
      message: 'Êtes vous sur de vos changements ?',
      rules: {
        reason: [
          {
            required: true,
            message: 'Merci de sélectionner une raison',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  computed: {
    type() {
      if (this.form.state == 'En attente de validation') {
        return 'warning'
      }
      return 'default'
    },
    canEditStatut() {
      if (this.$store.getters.contextRole == 'admin') {
        return true
      }
      return ['En attente de validation', 'Validée'].includes(this.form.state)
        ? true
        : false
    },
    statesAvailable() {
      if (this.$store.getters.contextRole == 'admin') {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) => item.value != this.form.state
        )
      } else {
        if (this.form.state == 'En attente de validation') {
          return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
            (item) => ['Validée', 'Refusée'].includes(item.value)
          )
        } else {
          return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
            (item) =>
              !['En attente de validation', 'Validée', 'Refusée'].includes(
                item.value
              ) && item.value != this.form.state
          )
        }
      }
    },
  },
  methods: {
    handleDeclineSubmit() {
      this.$refs['messageForm'].validate((valid) => {
        if (valid) {
          //
        }
      })
    },
    onSubmitState(state) {
      if (state == 'Refusée') {
        this.dialogVisible = true
        return
      }
      if (state == 'Validée') {
        this.message = `Vous êtes sur le point de <b>valider</b> la participation. Le bénévole sera notifié de ce changement.`
      }
      if (state == 'Effectuée') {
        this.message = `Le réserviste a terminé la mission. Il sera notifié de ce changement.`
      }
      if (state == 'Annulée') {
        this.message = `Vous ou le bénéficiaire n'êtes plus en mesure d'assurer la mission, le réserviste sera averti automatiquement.`
      }

      this.$confirm(this.message, 'Changement de statut', {
        confirmButtonText: 'Je confirme',
        cancelButtonText: 'Annuler',
        dangerouslyUseHTMLString: true,
      })
        .then(() => {
          this.form.state = state
          updateParticipation(this.form.id, this.form)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Le statut de la participation a été mis à jour',
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

<style lang="sass" scoped>
.el-form-item
  @apply mb-2
  .el-form-item__label
    @apply font-semibold
  ::v-deep
    .el-radio
      .el-radio__inner
        width: 20px
        height: 20px
        border-color: #F3F3F3
        background: #F3F3F3
        transition: all .25s
        box-shadow: none !important
        &::after
          background: url(/images/check-gray.svg)
          width: 11px
          height: 100%
          background-repeat: no-repeat
          background-position: center
          transform: translate(-50%, -50%) scale(1)
      .el-radio__input.is-checked
        .el-radio__inner
          border-color: #E6EAF5
          background: #E6EAF5
          &::after
            background: url(/images/check-primary.svg)
            background-repeat: no-repeat
            background-position: center
</style>
