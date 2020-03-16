<template>
  <div>
    <el-form
      ref="activityActeursForm"
      :model="form"
      label-position="top"
      :rules="rules"
      class="max-w-lg mt-5"
    >
      <el-form-item label="Responsabilité métier" prop="responsabilite_metier">
        <el-select v-model="form.responsabilite_metier" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].responsabilite_metier.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Responsabilité SI" prop="responsabilite_si">
        <el-select v-model="form.responsabilite_si" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].responsabilite_si.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Contributeur SI" prop="contributeur_si">
        <el-select v-model="form.contributeur_si" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].contributeur_si.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Contributeurs métier" prop="contributeur_metier">
        <el-select v-model="form.contributeur_metier" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].contributeur_metier.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="AMOA" prop="amoa">
        <el-select v-model="form.amoa" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].amoa.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="AMOE" prop="amoe">
        <el-select v-model="form.amoe" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].amoe.items"
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
      this.$refs["activityActeursForm"].validate(valid => {
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
