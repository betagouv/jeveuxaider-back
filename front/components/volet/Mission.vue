<template>
  <Volet v-if="$store.getters['volet/active']">
    <div
      v-if="row.structure.name"
      class="text-xs text-gray-600 uppercase text-center mt-8 mb-12"
    >
      {{ row.structure.name }}
    </div>
    <el-card shadow="never" class="overflow-visible">
      <div slot="header" class="clearfix flex flex-col items-center">
        <div class="-mt-10">
          <Avatar v-if="row.structure.name" :fallback="row.structure.name[0]" />
        </div>
        <nuxt-link
          class="font-semibold text-sm my-3 text-primary text-center"
          :to="`/dashboard/mission/${row.id}`"
          >{{ row.name }}
        </nuxt-link>
        <div class="flex items-center">
          <nuxt-link class="mr-1" :to="`/dashboard/mission/${row.id}`">
            <el-button icon="el-icon-view" type="mini">Voir</el-button>
          </nuxt-link>
          <nuxt-link :to="`/dashboard/mission/${row.id}/edit`">
            <el-button icon="el-icon-edit" type="mini">Modifier</el-button>
          </nuxt-link>
          <el-button
            v-if="canClone"
            class="ml-1"
            icon="el-icon-document-copy"
            type="mini"
            @click="clone(row.id)"
            >Dupliquer</el-button
          >
          <button
            v-if="
              $store.getters.contextRole == 'admin' ||
              $store.getters.contextRole == 'referent' ||
              $store.getters.contextRole == 'referent_regional' ||
              row.state == 'Brouillon'
            "
            type="button"
            class="ml-1 el-button is-plain el-button--danger el-button--mini"
            @click="onClickDelete"
          >
            <i class="el-icon-delete" />
          </button>
        </div>
      </div>
      <ModelMissionInfos :mission="row" />
    </el-card>
    <el-form ref="missionForm" :model="form" label-position="top">
      <template v-if="showAskValidation">
        <div class="mb-6 mt-12 flex text-xl text-gray-800">
          Publier la mission
        </div>
        <item-description container-class="mb-6">
          Une fois votre mission complétée, vous pouvez la publier pour qu'elle
          soit proposée aux utilisateurs.
        </item-description>
        <div class="flex pt-2">
          <el-button
            type="primary"
            :loading="loading"
            @click="onAskValidationSubmit"
            >Publier la mission</el-button
          >
        </div>
      </template>
    </el-form>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: { ...this.$store.getters['volet/row'] },
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
    showAskValidation() {
      return !!(
        this.$store.getters.contextRole == 'responsable' &&
        this.row.state == 'Brouillon'
      )
    },
    canClone() {
      const roles = ['admin', 'referent', 'responsable']
      return roles.includes(this.$store.getters.contextRole)
    },
  },
  methods: {
    clone(id) {
      this.loading = true
      this.$api.cloneMission(id).then((response) => {
        this.$router
          .push({
            path: `/dashboard/mission/${response.data.id}/edit`,
          })
          .then(() => {
            this.$message({
              message: 'La mission a été dupliquée !',
              type: 'success',
            })
          })
      })
    },
    onClickDelete() {
      if (this.row.participations_total > 0) {
        this.$alert(
          'Il est impossible de supprimer une mission déjà assigner à un ou plusieurs bénévoles.',
          'Supprimer la mission',
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        this.$confirm(
          `La mission ${this.row.name} sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
          'Supprimer la mission',
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteMission(this.row.id).then(() => {
            this.$message({
              type: 'success',
              message: `La mission ${this.row.name} a été supprimée.`,
            })
            this.$emit('deleted', this.row)
            // this.$store.commit('volet/setRow', null)
            this.$store.commit('volet/hide')
          })
        })
      }
    },
    onAskValidationSubmit() {
      if (this.form.structure.state != 'Validée') {
        this.$message.error({
          message:
            'Votre structure doit être validée avant de pouvoir publier une mission',
        })
      } else {
        if (this.form.template_id) {
          this.form.state = 'Validée'
        } else {
          this.form.state = 'En attente de validation'
        }
        this.onSubmit()
      }
    },
    onSubmit() {
      if (
        this.form.structure.state != 'Validée' &&
        this.form.state == 'Validée'
      ) {
        this.$message.error({
          message:
            "Vous devez valider l'organisation au préalable. Les missions en attente de validation seront ensuite automatiquement validées",
        })
      } else {
        let message = 'Êtes vous sur de vos changements ?'

        if (this.form.state == 'Annulée') {
          message = `Attention, vous êtes sur le point d'annuler une mission en lien avec ${this.form.participations_count} participation(s).<br><br> Les participations liées seront automatiquement annulées et les bénévoles inscrits seront notifiés de l'annulation de la mission.<br><br> Êtes vous sûr de vouloir continuer ?`
        }

        if (this.form.state == 'Terminée') {
          message = `Les participations en attente de validation seront automatiquement déclinées et celles validées passeront au statut mission effectuée.<br><br>Les bénévoles seront notifiés de ces modifications.<br><br> Êtes vous sûr de vouloir continuer ?`
        }

        if (this.form.state == 'Signalée') {
          message = `Vous êtes sur le point de signaler une mission qui ne répond pas aux exigences de la charte ou des règles fixés par le Décret n° 2017-930 du 9 mai 2017 relatif à la Réserve Civique. Le responsable est en lien avec ${this.form.participations_count} bénévole(s). <br><br> Les participations à venir seront automatiquement annulées. Les coordonnées des bénévoles seront masquées.`
        }

        this.$confirm(message, 'Confirmation', {
          confirmButtonText: 'Je confirme',
          cancelButtonText: 'Annuler',
          type: 'warning',
          center: true,
          dangerouslyUseHTMLString: true,
        })
          .then(() => {
            this.loading = true
            this.$api
              .updateMission(this.form.id, this.form)
              .then(({ data }) => {
                this.loading = false
                this.$store.commit('volet/setRow', { ...this.row, ...data })
                this.$message.success({
                  message: 'La mission a été mise à jour',
                })
                this.$emit('updated', { ...this.form, ...data })
              })
              .catch((error) => {
                this.loading = false
                console.log(error)
              })
          })
          .catch(() => {})
      }
    },
  },
}
</script>
