<template>
  <div>
    <el-form
      ref="activityTemporabiliteForm"
      :model="form"
      label-position="top"
      :rules="rules"
      class="max-w-lg mt-5"
    >
      <el-form-item label="Enjeu métier" prop="enjeu_metier">
        <el-select
          v-model="form.enjeu_metier"
          clearable
          placeholder="Choisissez une option"
        >
          <el-option
            v-for="item in $store.getters['app/taxonomies'].enjeu_metier.items"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          >
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Niveau d'enjeu" prop="niveau_enjeu">
        <el-select
          v-model="form.niveau_enjeu"
          clearable
          placeholder="Choisissez un niveau"
        >
          <el-option
            v-for="item in $store.getters['app/taxonomies'].niveau_enjeu.items"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          >
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Niveau de faisabilite" prop="niveau_faisabilite">
        <el-select
          v-model="form.niveau_faisabilite"
          clearable
          placeholder="Choisissez un niveau"
        >
          <el-option
            v-for="item in $store.getters['app/taxonomies'].niveau_faisabilite
              .items"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          >
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Risques à ne pas faire" prop="risque_a_ne_pas_faire">
        <el-input
          v-model="form.risque_a_ne_pas_faire"
          type="textarea"
          :row="3"
        />
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
    };
  },
  methods: {
    handleSubmit() {
      this.$refs["activityTemporabiliteForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          this.$emit("saved", this.form);
          this.loading = false;
        }
      });
    }
  }
};
</script>
