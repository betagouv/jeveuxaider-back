<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Utilisateur</div>
        <div class="mb-8 flex">
          <div class="font-bold text-2-5xl text-gray-800">
            {{ profile.first_name }} {{ profile.last_name }}
          </div>
          <TagProfileRoles
            :profile="profile"
            size="medium"
            class="flex items-center ml-3"
          />
        </div>
      </div>
      <div>
        <DropdownProfileButton :profile="profile" />
      </div>
    </div>
    <TabsUser :profile-id="profile.id" />

    <TableParticipations
      :loading="$fetchState.pending"
      :table-data="tableData"
      :on-updated-row="onUpdatedRow"
      :on-clicked-row="onClickedRow"
    />
    <div class="m-3 flex items-center">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="15"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      />
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>

    <portal to="volet">
      <VoletParticipation @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithFilters from '@/mixins/table-with-filters'
import TableWithVolet from '@/mixins/table-with-volet'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      !['admin', 'referent', 'referent_regional'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
    const profile = await $api.getProfile(params.id)
    return {
      profile,
    }
  },
  async fetch() {
    this.query['filter[profile.id]'] = this.profile.id
    const { data } = await this.$api.fetchParticipations(this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
}
</script>
