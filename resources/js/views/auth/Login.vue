<template>
  <div>
    <div class="text-4xl mb-10 text-gray-700">Réserve Civique</div>
    <el-form :model="form" ref="loginForm" label-position="top" :rules="rules">
      <el-form-item label="Email" prop="email">
        <el-input v-model="form.email"></el-input>
      </el-form-item>
      <el-form-item label="Mot de passe" prop="password">
        <el-input v-model="form.password" type="password"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="handleSubmit('loginForm')">Connexion</el-button>
      </el-form-item>
    </el-form>
    <router-link to="forgot-password" class="text-xs">Mot de passe oublié ?</router-link>
  </div>
</template>

<script type="text/babel">
export default {
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
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
        ],
        password: [
          {
            required: true,
            message: "Mot de passe obligatoire",
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
          this.$store.dispatch("auth/login", {
            email: this.form.email,
            password: this.form.password
          }).then((res) => {
            this.$router.push('/admin')
          })
        }
      });
    }
  }
};
</script>
