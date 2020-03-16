<template>
  <div>
    <el-form
      ref="passwordForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <el-form-item label="Mot de passe actuel" prop="current_password">
        <el-input
          v-model="form.current_password"
          placeholder="Renseignez votre mot de passe actuel"
          show-password
        />
      </el-form-item>
      <el-form-item label="Nouveau mot de passe" prop="password">
        <el-input
          v-model="form.password"
          placeholder="Choississez votre nouveau mot de passe"
          show-password
        />
      </el-form-item>
      <el-form-item
        label="Confirmez votre nouveau mot de passe"
        prop="password_confirmation"
      >
        <el-input
          v-model="form.password_confirmation"
          placeholder="Confirmez votre nouveau mot de passe"
          show-password
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

<script>
export default {
  data() {
    return {
      loading: false,
      form: {},
      rules: {
        current_password: [
          {
            required: true,
            message: "Veuillez renseigner votre mot de passe actuel",
            trigger: "blur"
          }
        ],
        password: [
          {
            required: true,
            message: "Veuillez renseigner un mot de passe",
            trigger: "blur"
          },
          {
            min: 8,
            message: "Votre mot de passe doit contenir au moins 8 caractères",
            trigger: "blur"
          }
        ],
        password_confirmation: [
          {
            required: true,
            message: "Veuillez confirmer votre mot de passe",
            trigger: "blur"
          },
          {
            min: 8,
            message: "Votre mot de passe doit contenir au moins 8 caractères",
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    handleSubmit() {
      this.$refs["passwordForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          this.$store.dispatch('user/updateCurrentUserPassword', this.form)
          .then(response => {
            this.loading = false;
            this.$message({
                message: 'Votre mot de passe a été mis à jour',
                type: "success"
              });
          })
        }
      });
    }
  }
};
</script>
