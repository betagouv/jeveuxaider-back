<template>
  <div>
    <div class="header flex justify-between">
      <div class="mb-5">
        <h1 v-if="!$route.params.bid" class="text-3xl">Ajout {{ $route.query.type }}</h1>
        <h1 v-else class="text-3xl">Modification {{ form.type }}</h1>
        <div class="text-xl text-gray-600">{{ activity.libelle }}</div>
      </div>
      <div class="actions">
        <el-button type="primary" plain @click="$router.back()">
          Retour
        </el-button>
      </div>
    </div>
    <el-form
      ref="activityActeursForm"
      :model="form"
      label-position="top"
      :rules="rules"
      class="max-w-lg mt-5"
    >
      <el-form-item label="Année" prop="annee">
        <el-select v-model="form.year" placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].annees.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Typologie" prop="nature">
        <el-select v-model="form.nature" placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].budget_nature.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item :label="label" prop="amount">
        <el-input v-model="form.amount" />
      </el-form-item>
      <el-form-item label="Commentaire" prop="note">
        <el-input v-model="form.note" type="textarea" :row="5" />
      </el-form-item>
      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="handleSubmit">
          Enregistrer
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script type="text/babel">

import { fetchActivity, fetchBudget } from "@/api/activity"
import { addOrUpdateBudget } from "@/api/activity"

export default {
  props: {
    type: String
  },
  data() {
    return {
      loading: false,
      form: {},
      activity: {},
      rules: {}
    }
  },
  created() {
    fetchActivity(this.$route.params.id).then((res) => {
        this.activity = res.data
    })
    if(this.$route.params.bid) {
      this.loading = true
      fetchBudget(this.$route.params.id, this.$route.params.bid).then((res) => {
        this.form = res.data
        this.loading = false
      })
    } else {
      this.form.type = this.$route.query.type
    }
  },
  computed: {
    label() {
      return this.$route.query.type == 'Budget' ? 'Montant (€)' : 'Total (JH)'
    }
  },
  methods: {
    handleSubmit() {
      addOrUpdateBudget(this.$route.params.id, this.form.id, this.form)
      .then((res) => {
        this.loading = false
        this.form = res.data
        this.$message({
          message: 'Le budget a été enregistrée',
          type: "success"
        })
        this.$router.push({ name: 'activity.view', params: { id: this.$route.params.id}})
      }).catch(err => {
        this.loading = false
      })
    }
  }
};
</script>
