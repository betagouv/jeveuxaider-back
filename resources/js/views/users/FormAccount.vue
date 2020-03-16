<template>
  <div>
    <el-form
      ref="accountForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <el-form-item label="Adresse email" prop="email">
        <el-input v-model="form.email" placeholder="Email" />
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
      form: {...this.$store.getters['user/currentUser']},
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
    handleSubmit() {
      this.$refs["accountForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          this.$store.dispatch('user/updateCurrentUser', this.form)
            .then(response => {
              this.loading = false;
              this.$message({
                message: 'Votre email a été mis à jour',
                type: "success"
              });
            })
        }
      });
    }
  }
};
</script>
