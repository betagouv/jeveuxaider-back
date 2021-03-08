<template>
  <DashboardVolet v-if="$store.getters['volet/active']">
    <template #content="{ row }">
      <div class="text-xs text-gray-600 uppercase text-center mt-8 mb-12"></div>
      <el-card shadow="never" class="overflow-visible relative">
        <div slot="header" class="clearfix flex flex-col items-center">
          <div class="-mt-10">
            <el-avatar class="bg-primary">
              {{ row.name[0] }}
            </el-avatar>
          </div>
          <nuxt-link
            class="font-semibold text-sm my-3 text-primary text-center"
            :to="`/dashboard/collectivity/${row.id}`"
          >
            {{ row.name }}
          </nuxt-link>
          <div class="flex items-center">
            <nuxt-link :to="`/dashboard/collectivity/${row.id}`">
              <el-button class="mr-1" icon="el-icon-view" type="mini">
                Voir
              </el-button>
            </nuxt-link>
            <nuxt-link
              v-if="$store.getters.contextRole == 'admin'"
              :to="`/dashboard/collectivity/${row.id}/edit`"
            >
              <el-button icon="el-icon-edit" type="mini"> Modifier </el-button>
            </nuxt-link>
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
        <DashboardModelCollectivityInfos :collectivity="row" />
      </el-card>
      <div class="text-lg mt-6 mb-2">Responsables</div>
      <el-card
        v-if="row.structure"
        shadow="never"
        class="overflow-visible relative"
      >
        <DashboardModelMemberTeaser
          v-for="member in row.structure.members"
          :key="member.id"
          class="member py-2"
          :member="member"
        />
      </el-card>
      <el-card v-else shadow="never" class="overflow-visible relative">
        Cette collectivité n'a pas d'organisation liée, donc pas de
        responsables.
      </el-card>
    </template>
  </DashboardVolet>
</template>

<script>
import VoletRow from '@/mixins/volet-row'
import { Message, MessageBox } from 'element-ui'

export default {
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
      MessageBox.confirm(
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
        this.$api.deleteCollectivity(this.form.id).then(() => {
          Message.success({
            message: `La collectivité a été supprimée.`,
          })
          this.$store.commit('volet/hide')
          this.$emit('deleted', this.form)
        })
      })
    },
    // onUpdated(row) {
    //   this.$store.commit('volet/setRow', row)
    //   this.$emit('updated', row)
    // },
  },
}
</script>
