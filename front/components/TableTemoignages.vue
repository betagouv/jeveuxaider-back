<template>
  <el-table
    v-loading="loading"
    :data="tableData"
    :highlight-current-row="true"
    @row-click="onClickedRow"
  >
    <el-table-column width="70" align="center">
      <template slot-scope="scope">
        <Avatar
          class="m-auto"
          :fallback="scope.row.participation.profile.short_name"
        />
      </template>
    </el-table-column>
    <el-table-column prop="name" label="Bénévole" width="250">
      <template slot-scope="scope">
        <div class="text-gray-900">
          {{ scope.row.participation.profile.full_name }}
        </div>
        <div class="font-light text-gray-600">
          <div class="text-xs">
            {{ scope.row.participation.profile.email }}
          </div>
          <div class="text-xs">
            {{ scope.row.participation.profile.mobile }} -
            {{ scope.row.participation.profile.zip }}
          </div>
        </div>
      </template>
    </el-table-column>

    <el-table-column prop="grade" label="Note" width="150">
      <template slot-scope="scope">
        <StarRating
          :rating="scope.row.grade"
          class="!relative !bottom-[2px]"
          :show-rating="false"
          inactive-color="#E0E0E0"
          active-color="#EF9F03"
          :read-only="true"
          :star-size="16"
        />
      </template>
    </el-table-column>

    <el-table-column prop="testimony" label="Témoignage" min-width="320">
      <template slot-scope="scope">
        <div class="text-xs">
          <v-clamp :max-lines="2" autoresize>
            {{ scope.row.testimony }}
          </v-clamp>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      v-if="!$store.getters['volet/active']"
      label="Crée le"
      width="150"
    >
      <template slot-scope="scope">
        <div class="text-sm text-secondary break-normal">
          {{ scope.row.created_at | fromNow }}
        </div>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  props: {
    tableData: {
      type: Array,
      default: null,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    onUpdatedRow: {
      type: Function,
      default: () => {},
    },
    onClickedRow: {
      type: Function,
      default: () => {},
    },
  },
}
</script>
