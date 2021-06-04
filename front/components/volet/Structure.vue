<template>
  <Volet v-if="$store.getters['volet/active']">
    <div class="text-xs text-gray-600 uppercase text-center mt-8 mb-12">
      {{ row.statut_juridique }}
    </div>
    <el-card shadow="never" class="overflow-visible relative">
      <div slot="header" class="clearfix flex flex-col items-center">
        <div class="-mt-10">
          <Avatar
            :source="
              row.logo
                ? row.logo.thumb
                  ? row.logo.thumb
                  : row.logo.original
                : null
            "
            :fallback="row.name[0]"
          />
        </div>
        <nuxt-link
          class="font-semibold text-sm my-3 text-primary text-center"
          :to="`/dashboard/structure/${row.id}`"
        >
          {{ row.name }}
        </nuxt-link>
        <div class="flex items-center">
          <nuxt-link :to="`/dashboard/structure/${row.id}`">
            <el-button class="mr-1" icon="el-icon-view" type="mini">
              Voir
            </el-button>
          </nuxt-link>
          <nuxt-link :to="`/dashboard/structure/${row.id}/edit`">
            <el-button icon="el-icon-edit" type="mini"> Modifier </el-button>
          </nuxt-link>
          <el-button
            v-if="
              $store.getters.contextRole == 'admin' ||
              $store.getters.contextRole == 'referent' ||
              $store.getters.contextRole == 'referent_regional'
            "
            type="button"
            class="ml-1 el-button is-plain el-button--danger el-button--mini"
            @click="onClickDelete"
          >
            <i class="el-icon-delete" />
          </el-button>
        </div>
      </div>
      <div class="flex flex-wrap items-center justify-center mb-4">
        <el-tag v-if="row.is_reseau" size="small" class="m-1 ml-0">
          Tête de réseau
        </el-tag>
        <el-tag v-if="row.reseau_id" class="m-1 ml-0" size="small">
          {{ row.reseau_id | reseauFromValue }}
        </el-tag>
      </div>
      <ModelStructureInfos :structure="row" />
    </el-card>
    <el-form ref="structureForm" :model="form" label-position="top">
      <div class="mb-6 mt-12 flex text-xl text-gray-800">Réseau national</div>
      <div v-if="$store.getters.contextRole !== 'referent'">
        <ItemDescription container-class="mb-6">
          Si votre organisation est membre d'un réseau national (Les Banques
          alimentaires, Armée du Salut...), renseignez son nom. Vous permettez
          ainsi au superviseur de votre réseau de visualiser les missions et
          bénévoles rattachés à votre organisation.
        </ItemDescription>
        <el-form-item label="Réseau national" prop="reseau" class="flex-1">
          <el-select
            v-model="form.reseau_id"
            clearable
            filterable
            placeholder="Réseau national"
          >
            <el-option
              v-for="item in $store.getters.reseaux"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
      </div>
      <el-form-item label="Tête de réseau" prop="is_reseau" class="flex-1">
        <el-checkbox v-model="form.is_reseau">
          <span class="text-xs font-light text-gray-600">
            Cette organisation est une tête de réseau
          </span>
        </el-checkbox>
      </el-form-item>
      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Enregistrer
        </el-button>
      </div>
    </el-form>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: {},
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
      handler(newValue, oldValue) {
        this.form = { ...newValue }
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
