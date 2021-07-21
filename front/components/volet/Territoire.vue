<template>
  <Volet :title="row.name" :link="`/dashboard/territoire/${row.id}`">
    <div class="flex flex-col space-y-6">
      <!-- ACTIONS -->
      <div class="flex flex-wrap space-x-2">
        <nuxt-link :to="`/dashboard/territoire/${row.id}/edit`">
          <el-button
            v-tooltip="{
              content: 'Modifier le territoire',
              classes: 'bo-style',
            }"
            icon="el-icon-edit"
            size="medium"
            >Modifier</el-button
          >
        </nuxt-link>

        <el-button
          v-if="$store.getters.contextRole == 'admin'"
          v-tooltip="{
            content: 'Supprimer le territoire',
            classes: 'bo-style',
          }"
          type="danger"
          size="medium"
          plain
          @click="onClickDelete"
        >
          <i class="el-icon-delete" />
        </el-button>
      </div>

      <!-- LIGNE -->
      <VoletCard v-if="territoire">
        <div class="flex space-x-4 items-center">
          <template
            v-if="territoire.state == 'validated' && territoire.is_published"
          >
            <div class="bg-green-500 rounded-full h-4 w-4"></div>
            <div class="text-lg text-gray-900">En ligne</div>
          </template>
          <template v-else>
            <div class="bg-red-500 rounded-full h-4 w-4"></div>
            <div class="text-lg text-gray-900">Hors ligne</div>
          </template>
        </div>

        <nuxt-link target="_blank" :to="territoire.full_url">
          <span class="text-sm underline hover:no-underline break-all">
            {{ $config.appUrl }}{{ territoire.full_url }}
          </span>
        </nuxt-link>
      </VoletCard>

      <!-- TERRITOIRE -->
      <VoletCard
        v-if="territoire"
        label="Territoire"
        :link="`/dashboard/territoire/${territoire.id}`"
        :icon="require('@/assets/images/icones/heroicon/library.svg?include')"
      >
        <!-- <VoletRowItem label="ID">{{ territoire.id }}</VoletRowItem> -->
        <VoletRowItem label="Nom"
          ><span class="font-bold">{{ territoire.name }}</span></VoletRowItem
        >
        <VoletRowItem label="Crée le">{{
          territoire.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          territoire.updated_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Statut">{{
          territoire.state | labelFromValue('territoires_states')
        }}</VoletRowItem>
        <VoletRowItem label="Type">{{
          territoire.type | labelFromValue('territoires_types')
        }}</VoletRowItem>
        <VoletRowItem v-if="territoire.type == 'city'" label="Zips">{{
          territoire.zips.join(', ')
        }}</VoletRowItem>
      </VoletCard>

      <!-- RESPONSABLE -->
      <template v-if="responsables.length > 0">
        <VoletCard
          v-for="responsable in responsables"
          :key="responsable.id"
          label="Responsable"
          :icon="require('@/assets/images/icones/heroicon/user.svg?include')"
          :link="`/dashboard/profile/${responsable.id}`"
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
      territoire: null,
      responsables: [],
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
        this.territoire = { ...newValue }
        const responsables = await this.$api.getTerritoireResponsables(
          this.territoire.id
        )
        this.responsables = responsables.data
      },
    },
  },
  methods: {
    onClickDelete() {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer ce territoire ?`,
        'Supprimer ce territoire',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteTerritoire(this.row.id).then(() => {
          this.$message.success({
            message: `Le territoire a été supprimée.`,
          })
          this.$emit('deleted', this.row)
          this.$store.commit('volet/hide')
        })
      })
    },
  },
}
</script>
