<template>
  <div>
    <div class="text-4xl mb-10 text-gray-700">Mot de passe oublié</div>
    <el-form :model="form" ref="form" label-position="top" :rules="rules">
      <el-form-item label="Email" prop="email">
        <el-input v-model="form.email"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="handleSubmit('form')">Demander un nouveau mot de passe</el-button>
      </el-form-item>
    </el-form>
    <router-link to="login" class="text-xs">J'ai retrouvé mes identifiants !</router-link>
  </div>
</template>

<script type="text/babel">
export default {
  data() {
    return {
      form: {},
      rules: {
        email: [
          {
            required: true,
            message: "Email obligatoire",
            trigger: "blur"
          },
          {
            type: "email",
            message: "Le format de l'email n'est pas correct",
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
          axios.post('password/email', this.form)
          .then((res) => {
            this.$message({
              message: 'Un email vous a été envoyé avec les instructions à suivre',
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
