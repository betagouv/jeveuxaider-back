<template>
  <div>
    <div class="text-4xl mb-5 text-gray-700">Réserve Civique</div>
    <el-form :model="form" ref="registerForm" label-position="top" :rules="rules">
      <el-form-item label="Email" prop="email">
        <el-input v-model="form.email" disabled></el-input>
      </el-form-item>
      <el-form-item label="Prénom" prop="first_name">
        <el-input v-model="form.first_name"></el-input>
      </el-form-item>
      <el-form-item label="Nom" prop="last_name">
        <el-input v-model="form.last_name"></el-input>
      </el-form-item>
      <el-form-item label="Mot de passe" prop="password">
        <el-input v-model="form.password" type="password"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="handleSubmit()">S'inscrire</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script type="text/babel">
export default {
  data() {
    return {
      form: {
        email: this.$route.query.email,
        first_name: this.$route.query.first_name,
        last_name: this.$route.query.last_name,
        password: ''
      },
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
      },
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
    };
  },
  methods: {
    handleSubmit() {
      this.$refs['registerForm'].validate(valid => {
        if (valid) {
          this.$store.dispatch("auth/register", this.form).then((res) => {
            this.$router.push('/admin')
          })
        }
      });
    }
  }
};
</script>
