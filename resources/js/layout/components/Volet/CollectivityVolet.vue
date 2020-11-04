<template>
  <Volet>
    <template v-slot:content="{ row }">
      <div class="text-xs text-gray-600 uppercase text-center mt-8 mb-12"></div>
      <el-card shadow="never" class="overflow-visible relative">
        <div slot="header" class="clearfix flex flex-col items-center">
          <div class="-mt-10">
            <el-avatar class="bg-primary">
              {{ row.name[0] }}
            </el-avatar>
          </div>
          <router-link
            class="font-semibold text-sm my-3 text-primary text-center"
            :to="{
              name: 'DashboardCollectivity',
              params: { id: row.id },
            }"
          >
            {{ row.name }}
          </router-link>
          <div class="flex items-center">
            <router-link
              :to="{
                name: 'DashboardCollectivity',
                params: { id: row.id },
              }"
            >
              <el-button class="mr-1" icon="el-icon-view" type="mini">
                Voir
              </el-button>
            </router-link>
            <router-link
              v-if="$store.getters.contextRole == 'admin'"
              :to="{
                name: 'CollectivityFormEdit',
                params: { id: row.id },
              }"
            >
              <el-button icon="el-icon-edit" type="mini"> Modifier </el-button>
            </router-link>
            <el-button
              v-if="$store.getters.contextRole == 'admin'"
              type="button"
              class="ml-1 el-button is-plain el-button--danger el-button--mini"
              @click="onClickDelete"
            >
              <i class="el-icon-delete" />
            </el-button>
          </div>
        </div>
        <div class="flex flex-wrap items-center justify-center mb-4">
          <el-tag v-if="row.published" size="small" class="m-1 ml-0">
            En ligne
          </el-tag>
          <el-tag v-if="!row.published" size="small" class="m-1 ml-0">
            Hors ligne
          </el-tag>
        </div>
        <collectivity-infos :collectivity="row" />
      </el-card>
    </template>
  </Volet>
</template>

<script>
import Volet from '@/layout/components/Volet'
import { deleteCollectivity } from '@/api/app'
import VoletRow from '@/mixins/VoletRow'
import ItemDescription from '@/components/forms/ItemDescription'
import CollectivityInfos from '@/components/infos/CollectivityInfos'

export default {
  name: 'CollectivityVolet',
  components: {
    Volet,
    ItemDescription,
    CollectivityInfos,
  },
  mixins: [VoletRow],
  data() {
    return {
      loading: false,
      form: {},
    }
  },
  computed: {},
  methods: {
    onClickDelete() {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer cette collectivité ?`,
        'Supprimer cette collectivité',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        deleteCollectivity(this.form.id).then(() => {
          this.$message({
            type: 'success',
            message: `La collectivité a été supprimée.`,
          })
          this.fetchDatas()
        })
      })
    },
    onUpdated(row) {
      this.$store.commit('volet/setRow', row)
      this.$emit('updated', row)
    },
  },
}
</script>
