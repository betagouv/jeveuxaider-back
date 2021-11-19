<template>
  <Volet :title="row.name" :link="`/dashboard/reseaux/${row.id}`">
    <div class="flex flex-col space-y-6">
      <!-- ACTIONS -->
      <div class="flex flex-wrap gap-2">
        <nuxt-link :to="`/dashboard/reseaux/${row.id}/edit`">
          <el-button
            v-tooltip="{
              content: 'Modifier le réseau',
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
            content: 'Supprimer le réseau',
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
      <VoletCard>
        <div class="flex space-x-4 items-center">
          <template v-if="row.is_published">
            <div class="bg-[#0e9f6e] rounded-full h-4 w-4"></div>
            <div class="text-lg text-gray-900">En ligne</div>
          </template>
          <template v-else>
            <div class="bg-[#f56565] rounded-full h-4 w-4"></div>
            <div class="text-lg text-gray-900">Hors ligne</div>
          </template>
        </div>

        <nuxt-link v-if="row.is_published" target="_blank" :to="row.full_url">
          <span class="text-sm underline hover:no-underline break-all">
            {{ $config.appUrl }}{{ row.full_url }}
          </span>
        </nuxt-link>
      </VoletCard>

      <!-- RÉSEAU -->
      <VoletCard
        label="Réseau"
        :link="`/dashboard/reseaux/${row.id}`"
        :icon="require('@/assets/images/icones/heroicon/library.svg?raw')"
      >
        <VoletRowItem label="ID">{{ row.id }}</VoletRowItem>
        <VoletRowItem label="Nom"
          ><span class="font-bold">{{ row.name }}</span></VoletRowItem
        >
        <VoletRowItem label="Nb. modèles">{{
          row.mission_templates_count
        }}</VoletRowItem>
        <VoletRowItem label="Crée le">{{
          row.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          row.updated_at | formatMediumWithTime
        }}</VoletRowItem>
      </VoletCard>

      <!-- STATISTICS -->
      <VoletCard>
        <router-link
          :to="`/dashboard/reseaux/${row.id}/structures`"
          class="flex space-x-4"
        >
          <div class="text-5xl leading-none text-gray-900">
            {{ row.missions_count }}
          </div>
          <div class="">
            <div class="text-lg text-gray-900">
              {{ row.missions_count | pluralize(['mission', 'missions']) }}
            </div>
            <div class="text-sm">
              sur {{ row.structures_count }}
              {{ row.structures_count | pluralize(['antenne', 'antennes']) }}
            </div>
          </div>
        </router-link>
      </VoletCard>

      <!-- RESPONSABLES -->
      <template v-if="responsables.length > 0">
        <VoletCard
          v-for="responsable in responsables"
          :key="responsable.id"
          label="Responsable"
          :icon="require('@/assets/images/icones/heroicon/user.svg?raw')"
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
      reseau: null,
      responsables: [],
      antennes: [],
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
      async handler(newValue, oldValue) {
        this.reseau = { ...newValue }
        const responsables = await this.$api.getReseauResponsables(
          this.reseau.id
        )
        this.responsables = responsables.data
      },
    },
  },
  methods: {
    onClickDelete() {
      if (this.reseau.structures_count > 0) {
        this.$alert(
          'Il est impossible de supprimer un réseau qui contient des antennes.',
          'Supprimer le réseau',
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        this.$confirm(
          `Le réseau ${this.row.name} sera définitivement supprimé. <br><br> Voulez-vous continuer ?<br>`,
          'Supprimer le réseau',
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            dangerouslyUseHTMLString: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteReseau(this.row.id).then(() => {
            this.$message.success({
              message: `Le réseau ${this.row.name} a été supprimé.`,
            })
            this.$emit('deleted', this.row)
            this.$store.commit('volet/hide')
          })
        })
      }
    },
  },
}
</script>
