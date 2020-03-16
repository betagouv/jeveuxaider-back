<template>
  <div>
    <div class="header flex justify-between">
      <h1 v-if="!$route.params.id" class="text-3xl mb-5">Création d'une activité</h1>
      <h1 v-else class="text-3xl mb-5">{{ form.libelle }}</h1>
      <div class="actions">
        <el-button type="primary" plain @click="$router.back()">
          Retour
        </el-button>
      </div>
    </div>
    <el-tabs>
      <el-tab-pane label="Infos">
        <form-informations :form="form" @saved="handleSubmit"></form-informations>
      </el-tab-pane>
      <el-tab-pane label="Acteurs">
        <form-acteurs :form="form" @saved="handleSubmit"></form-acteurs>
      </el-tab-pane>
      <el-tab-pane label="Temporabilité">
        <form-temporabilite :form="form" @saved="handleSubmit"></form-temporabilite>
      </el-tab-pane>
      <el-tab-pane label="Qualification">
        <form-qualification :form="form" @saved="handleSubmit"></form-qualification>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script type="text/babel">

import FormActeurs from '@/views/activity/FormActeurs.vue'
import FormInformations from '@/views/activity/FormInformations.vue'
import FormTemporabilite from '@/views/activity/FormTemporabilite.vue'
import FormQualification from '@/views/activity/FormQualification.vue'

import { fetchActivity } from "@/api/activity"
import { addOrUpdateActivity } from "@/api/activity"

export default {
  components: {
    FormActeurs,
    FormInformations,
    FormTemporabilite,
    FormQualification
  },
  data() {
    return {
      loading: false,
      form: {},
      rules: {}
    }
  },
  created() {
    this.loading = true
    if(this.$route.params.id) {
      fetchActivity(this.$route.params.id).then((res) => {
        this.form = res.data
        this.loading = false
      })
    }
  },
  methods: {
    handleSubmit(activity){
      addOrUpdateActivity(activity.id, activity)
      .then((res) => {
        this.loading = false
        this.form = res.data
        this.$message({
          message: 'L\'activité a été enregistrée',
          type: "success"
        })
        if(this.form.id) {
          this.$route.push({ name:'activity.edit', params: { id: this.form.id} })
        }
      }).catch(err => {
        this.loading = false
      })
    }
  }
};
</script>
