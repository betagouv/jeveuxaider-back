<template>
  <Volet
    :title="profile.full_name"
    :link="`/dashboard/participation/${row.id}`"
  >
    <div class="flex flex-col space-y-6">
      <!-- AVIS -->
      <VoletCard label="Avis">
        <VoletRowItem label="Note">
          <StarRating
            :rating="row.grade"
            class=""
            :show-rating="false"
            inactive-color="#E0E0E0"
            active-color="#EF9F03"
            :read-only="true"
            :star-size="16"
          />
        </VoletRowItem>

        <VoletRowItem label="Témoignage" class="whitespace-pre-line">{{
          row.testimony
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
        <VoletRowItem label="Nom">
          <span class="font-bold">
            {{ profile.full_name }}
          </span>
        </VoletRowItem>

        <VoletRowItem label="Type">
          <template v-if="profile.type">
            {{ profile.type | labelFromValue('profile_types') }}
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>

        <template v-if="canShowProfileDetails">
          <VoletRowItem label="Email">
            {{ profile.email }}
          </VoletRowItem>

          <VoletRowItem v-if="profile.mobile" label="Mobile">
            {{ profile.mobile }}
          </VoletRowItem>

          <VoletRowItem v-if="profile.birthday" label="Anniversaire">
            {{ profile.birthday }}
          </VoletRowItem>

          <VoletRowItem v-if="profile.zip" label="Zip">{{
            profile.zip
          }}</VoletRowItem>
        </template>

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

        <VoletRowItem v-if="profile.commitment__duration" label="Engagement">
          {{ profile.commitment__duration | labelFromValue('duration') }}
          <template v-if="profile.commitment__time_period">
            <span>par</span>
            <span>
              {{
                profile.commitment__time_period | labelFromValue('time_period')
              }}
            </span>
          </template>
        </VoletRowItem>

        <VoletRowItem label="Motivation">
          <template v-if="profile.description">
            <ReadMore
              more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
              more-str="Lire plus"
              :text="profile.description"
              :max-chars="120"
            ></ReadMore>
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

      <!-- PARTICIPATION -->
      <VoletCard
        v-if="participation"
        label="Participation"
        :icon="
          require('@/assets/images/icones/heroicon/identification.svg?include')
        "
        :link="`/dashboard/participation/${participation.id}`"
      >
        <VoletRowItem label="Statut">
          <span class="font-bold">
            {{ participation.state }}
          </span>
        </VoletRowItem>

        <VoletRowItem label="Crée le">
          {{ participation.created_at | formatMediumWithTime }}
        </VoletRowItem>

        <VoletRowItem label="Modifié le">
          {{ participation.updated_at | formatMediumWithTime }}
        </VoletRowItem>
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
          {{
            mission.publics_beneficiaires
              .map(function (item) {
                return $options.filters.labelFromValue(
                  item,
                  'mission_publics_beneficiaires'
                )
              })
              .join(', ')
          }}
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
    </div>
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
    participation() {
      return this.row.participation
    },
    profile() {
      return this.participation.profile
    },
    mission() {
      return this.participation.mission
    },
    canShowProfileDetails() {
      return !!(
        this.participation.mission &&
        (this.participation.mission.state != 'Signalée' ||
          this.$store.getters.contextRole !== 'responsable')
      )
    },
  },
  watch: {
    row: {
      immediate: true,
      deep: false,
      handler(newValue, oldValue) {
        this.form = { ...newValue }
      },
    },
  },
}
</script>
