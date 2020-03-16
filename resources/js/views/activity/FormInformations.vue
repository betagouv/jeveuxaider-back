<template>
  <div>
    <el-form
      ref="activityInformationsForm"
      :model="form"
      label-position="top"
      :rules="rules"
      class="max-w-lg mt-5"
    >
      <el-form-item label="Libellé" prop="libelle">
        <el-input v-model="form.libelle" />
      </el-form-item>
      <el-form-item label="Type d'activité" prop="type_activite">
        <el-select v-model="form.type_activite" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].type_activite.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Nature de l'activité" prop="nature_activite">
        <el-select v-model="form.nature_activite" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].nature_activite.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item v-if="form.type_activite == 'Projet'" label="Statut du projet" prop="statut">
        <el-select v-model="form.statut" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].statut_activite.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item v-if="form.type_activite == 'Projet'" label="Projet chapeau" prop="projet_chapeau">
        <el-input v-model="form.projet_chapeau" />
      </el-form-item>
      <el-form-item label="Contrainte réglementaire ou technique" prop="contrainte_reg_ou_tech">
        <el-select v-model="form.contrainte_reg_ou_tech" clearable placeholder="Choisissez une option">
          <el-option
            v-for="item in $store.getters['app/taxonomies'].contrainte.items"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Description" prop="description">
        <el-input v-model="form.description" type="textarea" :row="5" />
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
import { addOrUpdateActivity } from "@/api/activity"

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
      this.$refs["activityInformationsForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          if(this.form.type_activite != 'Projet') {
            this.form.projet_chapeau = ''
            this.form.statut = ''
          }
          this.$emit("saved", this.form);
          this.loading = false
        }
      });
    }
  }
};
</script>
