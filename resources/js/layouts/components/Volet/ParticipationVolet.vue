<template>
  <Volet>
    <template v-slot:content="{ row }">
      <el-card shadow="never" class="overflow-visible mt-24">
        <div slot="header" class="clearfix flex flex-col items-center">
          <div class="-mt-10">
            <el-avatar v-if="canShowProfileDetails" class="bg-primary">
              {{ row.profile.short_name }}
            </el-avatar>
            <el-avatar v-else class="bg-primary"> XX </el-avatar>
          </div>
          <router-link
            class="font-bold text-primary text-lg my-3"
            :to="{
              name: 'DashboardParticipation',
              params: { id: row.id },
            }"
          >
            <span v-if="canShowProfileDetails">
              {{ row.profile.full_name }}
            </span>
            <span v-else>Anonyme</span>
          </router-link>
          <div class="flex items-center">
            <router-link
              class="mr-1"
              :to="{
                name: 'DashboardParticipation',
                params: { id: row.id },
              }"
            >
              <el-button icon="el-icon-view" type="mini">Voir</el-button>
            </router-link>
            <button
              v-if="
                $store.getters.contextRole == 'admin' ||
                $store.getters.contextRole == 'referent' ||
                $store.getters.contextRole == 'referent_regional'
              "
              type="button"
              class="el-button is-plain el-button--danger el-button--mini"
              @click="onClickDelete"
            >
              <i class="el-icon-delete" />
            </button>
          </div>
        </div>
        <div class="flex items-center justify-center mb-4">
          <state-tag
            :state="row.state"
            size="small"
            class="flex items-center"
          />
        </div>
        <participation-infos :participation="row" />
      </el-card>
    </template>
  </Volet>
</template>

<script>
import Volet from '@/layouts/components/Volet'
import StateTag from '@/components/StateTag.vue'
import { updateParticipation, deleteParticipation } from '@/api/participation'
import VoletRow from '@/mixins/VoletRow'
import ParticipationInfos from '@/components/infos/ParticipationInfos'

export default {
  name: 'ParticipationVolet',
  components: { StateTag, Volet, ParticipationInfos },
  mixins: [VoletRow],
  data() {
    return {
      loading: false,
      form: {},
    }
  },
  computed: {
    canShowProfileDetails() {
      return this.row.mission &&
        (this.row.mission.state != 'Signalée' ||
          this.$store.getters.contextRole !== 'responsable')
        ? true
        : false
    },
    canChangeState() {
      let state = ['En attente de validation', 'Validée']
      return state.includes(this.row.state) === true ? true : false
    },
    statesAvailable() {
      if (this.$store.getters.contextRole == 'responsable') {
        return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
          (item) => item.value != 'Annulée'
        )
      } else {
        return this.$store.getters.taxonomies.participation_workflow_states
          .terms
      }
    },
  },
  methods: {
    onClickDelete() {
      this.$confirm(
        `La participation sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
        'Supprimer la participation',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        deleteParticipation(this.row.id).then(() => {
          this.$message({
            type: 'success',
            message: `La participation ${this.row.name} a été supprimée.`,
          })
          this.$emit('deleted', this.row)
          this.$store.commit('volet/setRow', null)
          this.$store.commit('volet/hide')
        })
      })
    },
    onSubmit() {
      this.$confirm(
        'Êtes vous sur de vos changements ?<br><br> Une notification sera envoyée au réserviste<br><br>',
        'Confirmation',
        {
          confirmButtonText: 'Je confirme',
          cancelButtonText: 'Annuler',
          dangerouslyUseHTMLString: true,
          center: true,
          type: 'warning',
        }
      ).then(() => {
        this.loading = true
        updateParticipation(this.form.id, this.form)
          .then((response) => {
            this.loading = false
            this.$message({
              type: 'success',
              message: 'La participation a été mise à jour',
            })
            this.$emit('updated', { ...this.form, ...response.data })
            this.$store.commit('volet/setRow', {
              ...this.row,
              ...response.data,
            })
          })
          .catch(() => {
            this.loading = false
          })
      })
    },
  },
}
</script>
