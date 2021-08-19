<template>
  <Volet
    :title="row.profile.full_name"
    :link="`/dashboard/participation/${row.id}`"
  >
    <div class="flex flex-col space-y-6">
      <!-- CONVERSATION -->
      <VoletCard
        v-if="conversation"
        :card-link="`/messages/${conversation.id}`"
      >
        <div class="flex items-center space-x-4">
          <div class="text-5xl leading-none text-[#070191]">
            <div
              class="text-[#070191] group-hover:text-[#070191] flex-shrink-0"
              v-html="
                require('@/assets/images/icones/heroicon/mail.svg?include')
              "
            />
          </div>
          <div class="">
            <div class="text-lg text-primary font-bold">
              Accéder à la conversation
            </div>
            <div class="text-sm">
              Dernier message le
              {{
                conversation.latest_message.created_at | formatMediumWithTime
              }}
            </div>
          </div>
        </div>
      </VoletCard>

      <!-- PARTICIPATION -->
      <VoletCard
        v-if="participation"
        label="Participation"
        :icon="
          require('@/assets/images/icones/heroicon/identification.svg?include')
        "
        :link="`/dashboard/participation/${participation.id}`"
      >
        <!-- <VoletRowItem label="ID">{{ participation.id }}</VoletRowItem> -->
        <VoletRowItem label="Statut"
          ><span class="font-bold">{{
            participation.state
          }}</span></VoletRowItem
        >
        <VoletRowItem label="Crée le">{{
          participation.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          participation.updated_at | formatMediumWithTime
        }}</VoletRowItem>
      </VoletCard>

      <!-- BENEVOLE -->
      <VoletCard
        v-if="profile"
        label="Bénévole"
        :icon="require('@/assets/images/icones/heroicon/user.svg?include')"
        :link="
          $store.getters.contextRole == 'admin'
            ? `/dashboard/profile/${profile.id}`
            : null
        "
      >
        <!-- <VoletRowItem label="ID">{{ profile.id }}</VoletRowItem> -->
        <VoletRowItem label="Nom"
          ><span class="font-bold">{{ profile.full_name }}</span></VoletRowItem
        >
        <VoletRowItem label="Type">
          <template v-if="profile.type">
            {{ profile.type | labelFromValue('profile_types') }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <template v-if="canShowProfileDetails">
          <VoletRowItem label="Email">{{ profile.email }}</VoletRowItem>
          <VoletRowItem v-if="profile.mobile" label="Mobile">{{
            profile.mobile
          }}</VoletRowItem>
          <VoletRowItem v-if="profile.birthday" label="Anniversaire">{{
            profile.birthday
          }}</VoletRowItem>
          <VoletRowItem v-if="profile.zip" label="Zip">{{
            profile.zip
          }}</VoletRowItem>
        </template>
        <VoletRowItem label="Domaines">
          <template v-if="profile.domaines && profile.domaines.length > 0">
            {{
              profile.domaines
                .map(function (item) {
                  return item.name.fr
                })
                .join(', ')
            }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Compétences">
          <template v-if="profile.skills && profile.skills.length > 0">
            {{
              profile.skills
                .map(function (item) {
                  return item.name.fr
                })
                .join(', ')
            }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Disponibilités">
          <template
            v-if="profile.disponibilities && profile.disponibilities.length > 0"
          >
            {{
              profile.disponibilities
                .map(function (item) {
                  return $options.filters.labelFromValue(
                    item,
                    'profile_disponibilities'
                  )
                })
                .join(', ')
            }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Crée le">{{
          profile.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          profile.updated_at | formatMediumWithTime
        }}</VoletRowItem>

        <VoletRowItem label="Dernière co.">{{
          profile.last_online_at | fromNow
        }}</VoletRowItem>
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
        <VoletRowItem label="Statut">{{ mission.state }}</VoletRowItem>
        <VoletRowItem label="Places restantes">
          {{ mission.places_left }}
        </VoletRowItem>
        <VoletRowItem label="Participation max">
          {{ mission.participations_max }}
        </VoletRowItem>

        <VoletRowItem label="Type"> {{ mission.type }} </VoletRowItem>
        <VoletRowItem v-if="mission.start_date" label="Debut">
          {{ mission.start_date | formatLongWithTime }}</VoletRowItem
        >
        <VoletRowItem v-if="mission.end_date" label="Fin">
          {{ mission.end_date | formatLongWithTime }}
        </VoletRowItem>
        <VoletRowItem v-if="mission.commitment__duration" label="Engag. min.">
          {{ mission.commitment__duration | labelFromValue('duration') }}
          <template v-if="mission.commitment__time_period">
            <span>par</span>
            <span>
              {{
                mission.commitment__time_period | labelFromValue('time_period')
              }}
            </span>
          </template>
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
        <VoletRowItem label="Message">
          <template v-if="mission.information">
            <ReadMore
              more-class="cursor-pointer uppercase font-bold text-xs text-[#242526]"
              more-str="Lire plus"
              :text="mission.information"
              :max-chars="120"
            ></ReadMore>
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Présentation">
          <template v-if="mission.objectif">
            <ReadMore
              more-class="cursor-pointer uppercase font-bold text-xs text-[#242526]"
              more-str="Lire plus"
              :text="mission.objectif"
              :max-chars="120"
            ></ReadMore>
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
        <VoletRowItem label="Précisions">
          <template v-if="mission.description">
            <ReadMore
              more-class="cursor-pointer uppercase font-bold text-xs text-[#242526]"
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
      <template v-if="$store.getters.contextRole != 'responsable'">
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
            mission.structure.state
              | labelFromValue('structure_workflow_states')
          }}</VoletRowItem>
          <VoletRowItem label="Type">{{
            mission.structure.statut_juridique
              | labelFromValue('structure_legal_status')
          }}</VoletRowItem>
        </VoletCard>
      </template>
    </div>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: { ...this.$store.getters['volet/row'] },
      profile: null,
      participation: null,
      mission: null,
      responsable: null,
      conversation: null,
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
    canShowProfileDetails() {
      return !!(
        this.mission &&
        (this.mission.state != 'Signalée' ||
          this.$store.getters.contextRole !== 'responsable')
      )
    },
    // canChangeState() {
    //   const state = ['En attente de validation', 'Validée']
    //   return state.includes(this.row.state) === true
    // },
    // statesAvailable() {
    //   if (this.$store.getters.contextRole == 'responsable') {
    //     return this.$store.getters.taxonomies.participation_workflow_states.terms.filter(
    //       (item) => item.value != 'Annulée'
    //     )
    //   } else {
    //     return this.$store.getters.taxonomies.participation_workflow_states
    //       .terms
    //   }
    // },
  },
  watch: {
    row: {
      immediate: true,
      deep: false,
      async handler(newValue, oldValue) {
        this.form = { ...newValue }
        this.participation = { ...newValue }

        if (
          ['admin', 'responsable'].includes(this.$store.getters.contextRole)
        ) {
          this.conversation = await this.$api.getParticipationConversation(
            this.participation.id
          )
        }

        this.profile = await this.$api.getParticipationBenevole(
          this.participation.id
        )
        this.mission = await this.$api.getMission(this.participation.mission_id)
        this.responsable = await this.$api.getMissionResponsable(
          this.participation.mission_id
        )
      },
    },
  },
  methods: {
    // onClickDelete() {
    //   this.$confirm(
    //     `La participation sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
    //     'Supprimer la participation',
    //     {
    //       confirmButtonText: 'Supprimer',
    //       confirmButtonClass: 'el-button--danger',
    //       cancelButtonText: 'Annuler',
    //       center: true,
    //       type: 'error',
    //     }
    //   ).then(() => {
    //     this.$api.deleteParticipation(this.row.id).then(() => {
    //       this.$message.success({
    //         message: `La participation ${this.row.name} a été supprimée.`,
    //       })
    //       this.$emit('deleted', this.row)
    //       // this.$store.commit('volet/setRow', null)
    //       this.$store.commit('volet/hide')
    //     })
    //   })
    // },
    // onSubmit() {
    //   this.$confirm(
    //     'Êtes vous sur de vos changements ?<br><br> Une notification sera envoyée au réserviste<br><br>',
    //     'Confirmation',
    //     {
    //       confirmButtonText: 'Je confirme',
    //       cancelButtonText: 'Annuler',
    //       dangerouslyUseHTMLString: true,
    //       center: true,
    //       type: 'warning',
    //     }
    //   ).then(() => {
    //     this.loading = true
    //     this.$api
    //       .updateParticipation(this.form.id, this.form)
    //       .then((response) => {
    //         this.loading = false
    //         this.$message.success({
    //           message: 'La participation a été mise à jour',
    //         })
    //         this.$emit('updated', { ...this.form, ...response.data })
    //         this.$store.commit('volet/setRow', {
    //           ...this.row,
    //           ...response.data,
    //         })
    //       })
    //       .catch(() => {
    //         this.loading = false
    //       })
    //   })
    // },
  },
}
</script>
