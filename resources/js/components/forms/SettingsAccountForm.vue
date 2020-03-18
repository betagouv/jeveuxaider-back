<template>
  <div class="settings-account">
    <el-form
      ref="settingsAccountForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <el-form-item label="Adresse Email" prop="email">
        <el-input v-model="form.email" placeholder="Email" />
      </el-form-item>
      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Modifier l'email
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
export default {
  name: "SettingsAccountForm",
  data() {
    return {
      loading: false,
      form: {
        email: this.$store.getters.user.email
      },
      rules: {
        email: [
          {
            type: "email",
            message: "Le format de l'email n'est pas correct",
            trigger: "blur"
          },
          {
            required: true,
            message: "Veuillez renseigner votre email",
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["settingsAccountForm"].validate(valid => {
        if (valid) {
          this.$store
            .dispatch("user/update", this.form)
            .then(() => {
              this.loading = false;
              this.$message({
                message: "Votre email a été modifié",
                type: "success"
              });
            })
            .catch(() => {
              this.loading = false;
            });
          this.loading = false;
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>
