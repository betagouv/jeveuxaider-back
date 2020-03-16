<template>
  <div>
    <el-form
      ref="profileForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <el-form-item label="Prénom" prop="first_name">
        <el-input v-model="form.first_name" placeholder="Prénom" />
      </el-form-item>
      <el-form-item label="Nom" prop="last_name">
        <el-input v-model="form.last_name" placeholder="Nom" />
      </el-form-item>
      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="handleSubmit">
          Enregistrer
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: {...this.$store.getters['user/currentUser'].profile},
      rules: {
        first_name: [
          {
            required: true,
            message: "Veuillez renseigner votre prénom",
            trigger: "blur"
          }
        ],
        last_name: [
          {
            required: true,
            message: "Veuillez renseigner votre nom",
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    handleSubmit() {
      this.$refs["profileForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          this.$store.dispatch('user/updateCurrentProfile', this.form)
            .then(response => {
              this.loading = false;
              this.$message({
                message: 'Votre profil a été mis à jour',
                type: "success"
              });
            })
        }
      });
    }
  }
};
</script>
