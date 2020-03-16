<template>
  <div>
    <el-table
      :data="sortItems"
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
      row-key="id"
    >
      <el-table-column prop="year" label="Année" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.year }}</span>
        </template>
      </el-table-column>
      <el-table-column prop="typologie" label="Typologie">
        <template slot-scope="scope">
          <span>{{ scope.row.nature[0] }}{{ scope.row.year }}-{{ scope.row.iteration }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Montant">
        <template slot-scope="scope">
          <div class="">{{ scope.row.amount | formatNumber }}{{ suffix }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Modifié le">
        <template slot-scope="scope">
          <div class="">{{ scope.row.updated_at | formatLong }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Actions" width="200">
        <template slot-scope="scope">
          <router-link :to="{ name: 'activity.budget.edit', params: { id: $route.params.id, bid: scope.row.id }}">
            <el-button size="medium">
              <i class="el-icon-edit mr-1 font-bold"></i> Modifier
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script type="text/babel">

import { sortBudgetItems } from '@/services/helpers'
import { deleteBudget } from '@/api/activity'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    suffix: {
      type: String,
      default: '€'
    }
  },
  computed: {
    sortItems() {
      return sortBudgetItems(this.items)
    }
  },
  methods: {
    handleDelete(id, bid) {
      Swal.fire({
        title: 'Êtes- vous sûr ?',
        text: 'Cette action est irrévocable',
        icon: 'error',
        confirmButtonText: 'Supprimer',
        confirmButtonColor: '#E64942',
        cancelButtonText: 'Annuler',
        showCancelButton: true
      }).then((result) => {
        if (result.value) {
          deleteBudget(id, bid).then(res => {
            this.$emit('updated', res.data)
          })
        }
      })
    }
  }
};
</script>
