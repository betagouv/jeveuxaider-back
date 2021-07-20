<template>
  <Volet :title="row.full_name" :link="`/dashboard/profile/${row.id}`">
    <div
      v-if="profile"
      :class="[{ 'mt-12': !$store.getters.contextRole == 'admin' }]"
    >
      <div class="flex flex-col space-y-6">
        <!-- ACTIONS -->
        <div
          v-if="$store.getters.contextRole == 'admin'"
          class="flex flex-wrap space-x-2"
        >
          <nuxt-link :to="`/dashboard/profile/${profile.id}/edit`">
            <el-button
              v-tooltip="{
                content: 'Modifier cet utilisateur',
                classes: 'bo-style',
              }"
              size="medium"
              icon="el-icon-edit"
              >Modifier</el-button
            >
          </nuxt-link>
        </div>

        <!-- PROFILE -->
        <VoletCard
          v-if="profile"
          label="Bénévole"
          :icon="require('@/assets/images/icones/heroicon/user.svg?include')"
          :link="`/dashboard/profile/${profile.id}`"
        >
          <!-- <VoletRowItem label="ID">{{ profile.id }}</VoletRowItem> -->
          <VoletRowItem label="Nom"
            ><span class="font-bold">{{
              profile.full_name
            }}</span></VoletRowItem
          >
          <VoletRowItem label="Rôle(s)">
            <template v-if="profile.roles">
              <TagProfileRoles :profile="profile" size="mini" />
            </template>
            <template v-else> N/A </template>
          </VoletRowItem>
          <VoletRowItem label="Type">
            <template v-if="profile.type">
              {{ profile.type | labelFromValue('profile_types') }}
            </template>
            <template v-else> N/A </template>
          </VoletRowItem>
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
              v-if="
                profile.disponibilities && profile.disponibilities.length > 0
              "
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
          <VoletRowItem v-if="profile.commitment__hours" label="Engagement">
            {{ profile.commitment__hours }} heures par
            {{
              profile.commitment__time_period | labelFromValue('time_period')
            }}
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

        <template v-if="structures">
          <VoletCard
            v-for="structure in structures"
            :key="structure.id"
            label="Organisation"
            :link="`/dashboard/structure/${structure.id}`"
            :icon="
              require('@/assets/images/icones/heroicon/library.svg?include')
            "
          >
            <!-- <VoletRowItem label="ID">{{ structure.id }}</VoletRowItem> -->
            <VoletRowItem label="Nom"
              ><span class="font-bold">{{ structure.name }}</span></VoletRowItem
            >
            <VoletRowItem label="Statut">{{
              structure.state | labelFromValue('structure_workflow_states')
            }}</VoletRowItem>
            <VoletRowItem label="Type">{{
              structure.statut_juridique
                | labelFromValue('structure_legal_status')
            }}</VoletRowItem>
          </VoletCard>
        </template>

        <template v-if="territoires">
          <VoletCard
            v-for="territoire in territoires"
            :key="territoire.id"
            label="Territoire"
            :link="`/dashboard/territoire/${territoire.id}`"
            :icon="require('@/assets/images/icones/heroicon/globe.svg?include')"
          >
            <!-- <VoletRowItem label="ID">{{ territoire.id }}</VoletRowItem> -->
            <VoletRowItem label="Nom"
              ><span class="font-bold">{{
                territoire.name
              }}</span></VoletRowItem
            >
            <VoletRowItem label="Statut">
              {{ territoire.state | labelFromValue('territoires_states') }}
            </VoletRowItem>
            <VoletRowItem label="Type">
              {{ territoire.type | labelFromValue('territoires_types') }}
            </VoletRowItem>
            <VoletRowItem label="Département">
              {{ territoire.department | labelFromValue('departments') }}
            </VoletRowItem>
            <VoletRowItem label="Zips">
              {{ territoire.zips.join(', ') }}
            </VoletRowItem>
          </VoletCard>
        </template>

        <VoletCard
          v-if="reseau"
          label="Réseau"
          :link="`/dashboard/structure/${reseau.id}`"
          :icon="require('@/assets/images/icones/heroicon/globe.svg?include')"
        >
          <!-- <VoletRowItem label="ID">{{ reseau.id }}</VoletRowItem> -->
          <VoletRowItem label="Nom"
            ><span class="font-bold">{{ reseau.name }}</span></VoletRowItem
          >
          <VoletRowItem label="Statut">{{
            reseau.state | labelFromValue('structure_workflow_states')
          }}</VoletRowItem>
          <VoletRowItem label="Type">{{
            reseau.statut_juridique | labelFromValue('structure_legal_status')
          }}</VoletRowItem>
        </VoletCard>
      </div>
    </div>
  </Volet>
</template>

<script>
export default {
  props: {
    hidePersonalFields: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      loading: false,
      form: {},
      profile: null,
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
    structures() {
      return this.profile.structures ? this.profile.structures : null
    },
    territoires() {
      return this.profile.territoires ? this.profile.territoires : null
    },
    reseau() {
      return this.profile.reseau ? this.profile.reseau : null
    },
  },
  watch: {
    row: {
      immediate: true,
      deep: false,
      async handler(newValue, oldValue) {
        this.form = { ...newValue }
        this.profile = await this.$api.getProfile(this.form.id)
      },
    },
  },
  methods: {
    onSubmit() {
      this.$confirm('Êtes vous sur de vos changements ?<br>', 'Confirmation', {
        confirmButtonText: 'Je confirme',
        cancelButtonText: 'Annuler',
        dangerouslyUseHTMLString: true,
        center: true,
        type: 'warning',
      }).then(() => {
        this.loading = true
        this.$api
          .updateProfile(this.form.id, this.form)
          .then((response) => {
            this.loading = false
            this.$message.success({
              message: 'Le profil a été mis à jour',
            })
            this.$emit('updated', response)
          })
          .catch(() => {
            this.loading = false
          })
      })
    },
  },
}
</script>
