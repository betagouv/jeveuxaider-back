<template>
  <Volet>
    <template v-slot:content="{ row }">
      <el-card shadow="never" class="overflow-visible mt-12">
        <div slot="header" class="clearfix flex flex-col items-center">
          <div class="-mt-10">
            <el-avatar
              v-if="row.avatar"
              :src="`${row.avatar}`"
              class="w-10 rounded-full border"
            />
            <el-avatar v-else class="bg-primary">
              {{ row.first_name[0] }}{{ row.last_name[0] }}
            </el-avatar>
          </div>
          <router-link
            class="font-bold text-lg text-primary mb-2 mt-3"
            :to="{
              name: 'Profile',
              params: { id: row.id },
            }"
          >
            {{ row.full_name }}
          </router-link>
        </div>
        <div class="flex items-center justify-center mb-4">
          <profile-roles-tags v-if="row.roles" :profile="row" size="small" />
        </div>
        <profile-infos :profile="row" />
      </el-card>
      <template v-if="$store.getters.contextRole === 'admin'">
        <el-form ref="profileForm" :model="form" label-position="top">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Superviseur réseau national
          </div>
          <item-description>
            Si cet utilisateur est membre d'un réseau national (Les Banques
            alimentaires, Armée du Salut...), renseignez son nom. Vous permettez
            à cet utilisateur de visualiser les missions et bénévoles rattachés
            aux organisations de ce réseau national.
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
          <item-description>
            Si cet utilisateur est référent, renseignez le nom du département
            Vous permettez à cet utilisateur de visualiser les missions et
            bénévoles rattachés aux organisations de ce département.
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
    </template>
  </Volet>
</template>

<script>
import Volet from '@/layout/components/Volet'
import ProfileRolesTags from '@/components/ProfileRolesTags.vue'
import { updateProfile } from '@/api/user'
import VoletRow from '@/mixins/VoletRow'
import ItemDescription from '@/components/forms/ItemDescription'
import ProfileInfos from '@/components/infos/ProfileInfos'

export default {
  name: 'ProfileVolet',
  components: { ProfileRolesTags, Volet, ItemDescription, ProfileInfos },
  mixins: [VoletRow],
  data() {
    return {
      loading: false,
      form: {},
    }
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
        updateProfile(this.form.id, this.form)
          .then((response) => {
            this.loading = false
            this.$message({
              type: 'success',
              message: 'Le profil a été mis à jour',
            })
            this.$store.commit('volet/setRow', {
              ...this.row,
              ...response.data,
            })
            this.$emit('updated', { ...this.form, ...response.data })
          })
          .catch(() => {
            this.loading = false
          })
      })
    },
  },
}
</script>
