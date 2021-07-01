<template>
  <Volet>
    <el-card shadow="never" class="overflow-visible mt-12">
      <div slot="header" class="clearfix flex flex-col items-center">
        <div class="-mt-10">
          <Avatar
            :source="row.avatar ? row.avatar : null"
            :fallback="`${row.first_name[0]}${row.last_name[0]}`"
          />
        </div>
        <nuxt-link
          class="font-bold text-lg text-primary mb-2 mt-3"
          :to="`/dashboard/profile/${row.id}`"
        >
          <template v-if="hidePersonalFields">
            {{ row.first_name }} {{ row.last_name[0] }}.
          </template>
          <template v-else>
            {{ row.full_name }}
          </template>
        </nuxt-link>
      </div>
      <div class="flex items-center justify-center mb-4">
        <TagProfileRoles v-if="row.roles" :profile="row" size="small" />
      </div>
      <ModelProfileInfos :profile="row" />
    </el-card>
    <template v-if="$store.getters.contextRole === 'admin'">
      <el-form ref="profileForm" :model="form" label-position="top">
        <div class="mb-6 mt-12 flex text-xl text-gray-800">
          Tête de réseau national
        </div>
        <item-description container-class="mb-6">
          Si cet utilisateur est membre d'un réseau national (Les Banques
          alimentaires, Armée du Salut...), renseignez son nom. Vous permettez à
          cet utilisateur de visualiser les missions et bénévoles rattachés aux
          organisations de ce réseau national.
        </item-description>
        <el-form-item label="Réseau national" prop="reseau" class="flex-1">
          <el-select
            v-model="form.reseau_id"
            clearable
            placeholder="Sélectionner un réseau national"
          >
            <el-option
              v-for="item in $store.getters.reseaux"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <div class="mb-6 mt-12 flex text-xl text-gray-800">
          Référent départemental
        </div>
        <item-description container-class="mb-6">
          Si cet utilisateur est référent, renseignez le nom du département Vous
          permettez à cet utilisateur de visualiser les missions et bénévoles
          rattachés aux organisations de ce département.
        </item-description>

        <el-form-item
          label="Département"
          prop="referent_department"
          class="flex-1"
        >
          <el-select
            v-model="form.referent_department"
            filterable
            clearable
            placeholder="Département"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.departments.terms"
              :key="item.value"
              :label="`${item.value} - ${item.label}`"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Enregistrer
          </el-button>
        </div>
      </el-form>
    </template>
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
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
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
