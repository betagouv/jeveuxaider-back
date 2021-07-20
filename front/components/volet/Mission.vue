<template>
  <Volet :title="row.name" :link="`/dashboard/mission/${row.id}`">
    <div class="flex flex-col space-y-6">
      <!-- ACTIONS -->
      <div class="flex flex-wrap space-x-2">
        <template v-if="showAskValidation">
          <el-button
            type="primary"
            :loading="loading"
            icon="el-icon-upload2"
            size="medium"
            @click="onAskValidationSubmit"
            >Publier</el-button
          >
        </template>
        <nuxt-link :to="`/dashboard/mission/${row.id}/edit`">
          <el-button
            v-tooltip="{
              content: 'Modifier la mission',
              classes: 'bo-style',
            }"
            icon="el-icon-edit"
            size="medium"
            >Modifier</el-button
          >
        </nuxt-link>
        <el-button
          v-if="canClone"
          v-tooltip="{
            content: 'Dupliquer la mission',
            classes: 'bo-style',
          }"
          size="medium"
          icon="el-icon-document-copy"
          @click="clone(row.id)"
        ></el-button>
        <el-button
          v-if="
            $store.getters.contextRole == 'admin' ||
            ($store.getters.contextRole == 'responsable' &&
              row.state == 'Brouillon')
          "
          v-tooltip="{
            content: 'Supprimer la mission',
            classes: 'bo-style',
          }"
          type="danger"
          size="medium"
          plain
          @click="onClickDelete"
        >
          <i class="el-icon-delete" />
        </el-button>
      </div>

      <!-- EN LIGNE -->
      <VoletCard v-if="mission">
        <div class="flex space-x-4 items-center">
          <template
            v-if="
              mission.structure.state == 'Validée' &&
              ['Validée', 'Terminée'].includes(mission.state)
            "
          >
            <div class="bg-green-500 rounded-full h-4 w-4"></div>
            <div class="text-lg text-gray-900">En ligne</div>
          </template>
          <template v-else>
            <div class="bg-red-500 rounded-full h-4 w-4"></div>
            <div class="text-lg text-gray-900">Hors ligne</div>
          </template>
        </div>

        <nuxt-link
          target="_blank"
          :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
        >
          <span class="text-sm underline hover:no-underline">
            {{ $config.appUrl }}/missions-benevolat/{{ mission.id }}/{{
              mission.slug
            }}
          </span>
        </nuxt-link>
      </VoletCard>

      <!-- PLACES RESTANTES -->
      <VoletCard
        v-if="mission"
        label="Places"
        link-label="Trouver des bénévoles"
        :link="`/dashboard/mission/${mission.id}/trouver-des-benevoles`"
      >
        <div class="flex space-x-4">
          <div class="text-5xl leading-none text-gray-900">
            {{ mission.places_left }}
          </div>
          <div class="">
            <div class="text-lg text-gray-900">places restantes</div>
            <div class="text-sm">
              {{ mission.participations_max - mission.places_left }} sur
              {{ mission.participations_max }}
            </div>
          </div>
        </div>
      </VoletCard>

      <!-- MISSION -->
      <VoletCard
        v-if="mission"
        label="Mission"
        :icon="
          require('@/assets/images/icones/heroicon/collection.svg?include')
        "
        :link="`/dashboard/mission/${mission.id}`"
      >
        <!-- <VoletRowItem label="ID">{{ mission.id }}</VoletRowItem> -->
        <VoletRowItem label="Nom"
          ><span class="font-bold">{{ mission.name }}</span></VoletRowItem
        >
        <VoletRowItem label="Crée le">{{
          mission.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          mission.updated_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Statut">{{ mission.state }}</VoletRowItem>
        <VoletRowItem label="Type"> {{ mission.type }} </VoletRowItem>
        <VoletRowItem label="Format"> {{ mission.format }} </VoletRowItem>
        <VoletRowItem v-if="mission.start_date" label="Debut">
          {{ mission.start_date | formatLongWithTime }}</VoletRowItem
        >
        <VoletRowItem v-if="mission.end_date" label="Fin">
          {{ mission.end_date | formatLongWithTime }}
        </VoletRowItem>
        <VoletRowItem v-if="mission.commitment__hours" label="Engag. min.">
          {{ mission.commitment__hours }} heures par
          {{ mission.commitment__time_period | labelFromValue('time_period') }}
        </VoletRowItem>
        <VoletRowItem v-if="mission.domaine_name" label="Domaine">
          {{ mission.domaine_name }}
        </VoletRowItem>
        <VoletRowItem
          v-if="mission.publics_beneficiaires"
          label="Publics bénéf."
        >
          {{ mission.publics_beneficiaires.join(', ') }}
        </VoletRowItem>
        <VoletRowItem v-if="mission.publics_volontaires" label="Publics volon.">
          {{ mission.publics_volontaires.join(', ') }}
        </VoletRowItem>
        <VoletRowItem label="Compétences">
          <template v-if="mission.skills && mission.skills.length > 0">
            {{
              mission.skills
                .map(function (item) {
                  return item.name.fr
                })
                .join(', ')
            }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Adresse">
          {{ mission.full_address }}
        </VoletRowItem>
        <VoletRowItem label="Département">
          {{ mission.department | fullDepartmentFromValue }}
        </VoletRowItem>
        <VoletRowItem label="Information">
          <template v-if="mission.information">
            <ReadMore
              more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
              more-str="Lire plus"
              :text="mission.information"
              :max-chars="120"
            ></ReadMore>
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Objectif">
          <template v-if="mission.objectif">
            <ReadMore
              more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
              more-str="Lire plus"
              :text="mission.objectif"
              :max-chars="120"
            ></ReadMore>
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Règles">
          <template v-if="mission.description">
            <ReadMore
              more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
              more-str="Lire plus"
              :text="mission.description"
              :max-chars="120"
            ></ReadMore>
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
      </VoletCard>

      <!-- RESPONSABLE -->
      <VoletCard
        v-if="responsable"
        label="Responsable"
        :icon="require('@/assets/images/icones/heroicon/user.svg?include')"
        :link="
          $store.getters.contextRole == 'admin'
            ? `/dashboard/profile/${responsable.id}`
            : null
        "
      >
        <!-- <VoletRowItem label="ID">{{ responsable.id }}</VoletRowItem> -->
        <VoletRowItem label="Nom"
          ><span class="font-bold">{{
            responsable.full_name
          }}</span></VoletRowItem
        >
        <VoletRowItem label="Email">{{ responsable.email }}</VoletRowItem>
        <VoletRowItem label="Mobile">{{ responsable.mobile }}</VoletRowItem>
        <VoletRowItem v-if="responsable.phone" label="Tel">{{
          responsable.phone
        }}</VoletRowItem>
      </VoletCard>

      <!-- STRUCTURE -->
      <VoletCard
        v-if="mission && mission.structure"
        label="Organisation"
        :link="`/dashboard/structure/${mission.structure.id}`"
        :icon="require('@/assets/images/icones/heroicon/library.svg?include')"
      >
        <!-- <VoletRowItem label="ID">{{ mission.structure.id }}</VoletRowItem> -->
        <VoletRowItem label="Nom"
          ><span class="font-bold">{{
            mission.structure.name
          }}</span></VoletRowItem
        >
        <VoletRowItem label="Statut">{{
          mission.structure.state | labelFromValue('structure_workflow_states')
        }}</VoletRowItem>
        <VoletRowItem label="Type">{{
          mission.structure.statut_juridique
            | labelFromValue('structure_legal_status')
        }}</VoletRowItem>
      </VoletCard>
    </div>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: { ...this.$store.getters['volet/row'] },
      mission: null,
      responsable: null,
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
  watch: {
    row: {
      immediate: true,
      deep: false,
      async handler(newValue, oldValue) {
        this.form = { ...newValue }
        this.mission = await this.$api.getMission(this.row.id)
        this.responsable = await this.$api.getMissionResponsable(
          this.mission.id
        )
      },
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
