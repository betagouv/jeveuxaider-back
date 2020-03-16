<template>
  <div>
    <el-form
      ref="activityTemporabiliteForm"
      :model="form"
      label-position="top"
      :rules="rules"
      class="max-w-lg mt-5"
    >
      <el-form-item label="Début du projet" prop="debut_projet_annee">
        <el-select v-model="form.debut_projet_annee" clearable placeholder="Choisissez une année">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].annees.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Trimestre de début du projet" prop="debut_projet_trimestre">
        <el-select v-model="form.debut_projet_trimestre" clearable placeholder="Choisissez un trimestre">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].trimestres.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Échéance du projet" prop="fin_projet_annee">
        <el-select v-model="form.fin_projet_annee" clearable placeholder="Choisissez une année">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].annees.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Trimestre de fin du projet" prop="fin_projet_trimestre">
        <el-select v-model="form.fin_projet_trimestre" clearable placeholder="Choisissez un trimestre">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].trimestres.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
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
export default {
  props: {
    form: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      rules: {}
    }
  },
  methods: {
    handleSubmit(){
      this.$refs["activityTemporabiliteForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          this.$emit("saved", this.form);
          this.loading = false
        }
      });
    }
  }
};
</script>
