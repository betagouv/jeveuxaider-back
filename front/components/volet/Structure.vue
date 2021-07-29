<template>
  <Volet :title="row.name" :link="`/dashboard/structure/${row.id}`">
    <div class="flex flex-col space-y-6">
      <!-- ACTIONS -->
      <div class="flex flex-wrap space-x-2">
        <nuxt-link :to="`/dashboard/structure/${row.id}/edit`">
          <el-button
            v-tooltip="{
              content: 'Modifier l\'organisation',
              classes: 'bo-style',
            }"
            icon="el-icon-edit"
            size="small"
            >Modifier</el-button
          >
        </nuxt-link>

        <el-button
          v-if="$store.getters.contextRole == 'admin'"
          v-tooltip="{
            content: 'Supprimer l\'organisation',
            classes: 'bo-style',
          }"
          plain
          type="danger"
          size="small"
          @click="onClickDelete"
        >
          <i class="el-icon-delete" />
        </el-button>
      </div>

      <!-- LIGNE -->
      <VoletCard
        v-if="structure && structure.statut_juridique == 'Association'"
      >
        <div class="flex space-x-4 items-center">
          <template v-if="['Validée', 'Terminée'].includes(structure.state)">
            <div class="bg-green-500 rounded-full h-4 w-4"></div>
            <div class="text-lg text-gray-900">En ligne</div>
          </template>
          <template v-else>
            <div class="bg-red-500 rounded-full h-4 w-4"></div>
            <div class="text-lg text-gray-900">Hors ligne</div>
          </template>
        </div>

        <nuxt-link target="_blank" :to="structure.full_url">
          <span class="text-sm underline hover:no-underline break-all">
            {{ $config.appUrl }}{{ structure.full_url }}
          </span>
        </nuxt-link>
      </VoletCard>

      <!-- PARTICIPATIONS -->
      <VoletCard v-if="statistics">
        <div class="flex space-x-4">
          <div class="text-5xl leading-none text-gray-900">
            {{ statistics.participations.total }}
          </div>
          <div class="">
            <div class="text-lg text-gray-900">
              {{
                statistics.participations.total
                  | pluralize(['participation', 'participations'])
              }}
            </div>
            <div class="text-sm">
              sur {{ statistics.missions.total }}
              {{
                statistics.missions.total | pluralize(['mission', 'missions'])
              }}
            </div>
          </div>
        </div>
      </VoletCard>

      <VoletCard
        v-if="
          structure && structure.response_time && structure.participations_count
        "
      >
        <div class="flex space-x-4">
          <div class="text-5xl leading-none text-gray-900">
            {{ structure.response_time | daysFromTimestamp
            }}<span class="text-xl">j</span>
          </div>
          <div class="">
            <div class="text-lg text-gray-900">de temps de réponse</div>
            <div class="text-sm">
              <span>aux bénévoles </span>
              <template v-if="structure.response_time / (60 * 60 * 24) > 9">
                <span class="text-red-500">(mauvais)</span>
              </template>
              <template
                v-else-if="structure.response_time / (60 * 60 * 24) > 4"
              >
                <span class="text-orange-500">(moyen)</span>
              </template>
              <template v-else>
                <span class="text-green-500">(bon)</span>
              </template>
            </div>
          </div>
        </div>
      </VoletCard>

      <VoletCard
        v-if="
          structure &&
          structure.response_ratio &&
          structure.participations_count
        "
      >
        <div class="flex space-x-4">
          <div class="text-5xl leading-none text-gray-900">
            {{ structure.response_ratio }}<span class="text-xl">%</span>
          </div>
          <div class="">
            <div class="text-lg text-gray-900">de taux de réponse</div>
            <div class="text-sm flex items-center space-x-2">
              <span>aux candidatures</span>
              <span class="text-xs"
                >({{
                  structure.participations_count -
                  structure.waiting_participations_count
                }}/{{ structure.participations_count }})</span
              >
            </div>
          </div>
        </div>
      </VoletCard>

      <!-- STRUCTURE -->
      <VoletCard
        v-if="structure"
        label="Organisation"
        :link="`/dashboard/structure/${structure.id}`"
        :icon="require('@/assets/images/icones/heroicon/library.svg?include')"
      >
        <!-- <VoletRowItem label="ID">{{ structure.id }}</VoletRowItem> -->
        <VoletRowItem label="Nom"
          ><span class="font-bold">{{ structure.name }}</span></VoletRowItem
        >
        <VoletRowItem label="Crée le">{{
          structure.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          structure.updated_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Statut">{{
          structure.state | labelFromValue('structure_workflow_states')
        }}</VoletRowItem>
        <VoletRowItem label="Type">{{
          structure.statut_juridique | labelFromValue('structure_legal_status')
        }}</VoletRowItem>
        <VoletRowItem
          v-if="structure.structure_publique_type"
          label="Type (pub.)"
          >{{ structure.structure_publique_type }}</VoletRowItem
        >
        <VoletRowItem v-if="structure.association_types" label="Type (asso.)">{{
          structure.association_types.join(', ')
        }}</VoletRowItem>
        <VoletRowItem
          v-if="structure.structure_publique_etat_type"
          label="Corps"
          >{{ structure.structure_publique_etat_type }}</VoletRowItem
        >
        <VoletRowItem label="Adresse">
          {{ structure.full_address }}
        </VoletRowItem>
        <VoletRowItem v-if="structure.department" label="Département">
          {{ structure.department | fullDepartmentFromValue }}
        </VoletRowItem>

        <VoletRowItem label="Description">
          <template v-if="structure.description">
            <ReadMore
              more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
              more-str="Lire plus"
              :text="structure.description"
              :max-chars="120"
            ></ReadMore>
          </template>
          <template v-else> N/A </template>
        </VoletRowItem>
      </VoletCard>

      <!-- RESPONSABLE -->
      <template v-if="responsables.length > 0">
        <VoletCard
          v-for="responsable in responsables"
          :key="responsable.id"
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
      </template>
    </div>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: {},
      structure: null,
      responsables: [],
      statistics: null,
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
    showStatut() {
      return !!(this.row.state != 'Signalée' && this.row.state != 'Désinscrite')
    },
    statesAvailable() {
      return this.$store.getters.taxonomies.structure_workflow_states.terms.filter(
        (item) => item.value != 'Désinscrite'
      )
    },
  },
  watch: {
    row: {
      immediate: true,
      deep: false,
      async handler(newValue, oldValue) {
        this.form = { ...newValue }
        this.structure = { ...newValue }
        const responsables = await this.$api.getStructureMembers(
          this.structure.id
        )
        this.responsables = responsables.data

        const statistics = await this.$api.statisticsBySubject(
          'organisations',
          this.structure.id
        )
        this.statistics = statistics.data
      },
    },
  },
  methods: {
    onClickDelete() {
      if (this.row.missions_count > 0) {
        this.$alert(
          'Il est impossible de supprimer une organisation qui contient des missions.',
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Retour',
            type: 'warning',
          }
        )
      } else {
        this.$confirm(
          `L'organisation ${this.row.name} sera définitivement supprimée de la plateforme.<br><br> Voulez-vous continuer ?<br>`,
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            dangerouslyUseHTMLString: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteStructure(this.row.id).then(() => {
            this.$message.success({
              message: `L'organisation ${this.row.name} a été supprimée.`,
            })
            this.$emit('deleted', this.row)
            this.$store.commit('volet/hide')
            // this.$store.commit('volet/setRow', null)
          })
        })
      }
    },
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
          .updateStructure(this.form.id, this.form)
          .then((response) => {
            this.loading = false
            this.$message.success({
              message: "L'organisation a été mise à jour",
            })
            this.$emit('updated', response.data)
          })
          .catch((error) => {
            this.loading = false
            this.errors = error.response.data.errors
          })
      })
    },
  },
}
</script>
