<template>
  <div>
      <div class="text-4xl mb-10 text-gray-700 uppercase">Réinitialisation de votre mot de passe</div>
    <el-form :model="form" ref="form" label-position="top" :rules="rules">
      <el-form-item label="Nouveau mot de passe" prop="password">
        <el-input
          v-model="form.password"
          show-password
        />
      </el-form-item>
      <el-form-item
        label="Confirmez votre nouveau mot de passe"
        prop="password_confirmation"
      >
        <el-input
          v-model="form.password_confirmation"
          show-password
        />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="handleSubmit('form')">Réinitialiser</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script type="text/babel">
export default {
  data() {
    return {
      form: {
        token: this.$route.params.token
      },
      rules: {
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
    handleSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          axios.post('password/reset', this.form)
          .then((res) => {
           this.$message({
              message: 'Vous pouvez dès à présent vous connecter avec vos nouveaux identifiants',
              type: "success"
            });
            this.$router.push('/login')
          })
        }
      });
    }
  }
};
</script>
