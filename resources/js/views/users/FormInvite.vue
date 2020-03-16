<template>
  <div class="settings max-w-lg">
    <div class="mb-12 font-bold text-2xl text-gray-800">
      Inviter un collaborateur
    </div>
     <div class="mb-12">
      <div class="mb-6 text-xl text-gray-800">
        Profil
      </div>
      <div class="mb-6 text-xs text-gray-600 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        Votre collaborateur recevra un email l'invitant à s'inscrire.
      </div>
      <el-form
        ref="profileForm"
        :model="form"
        label-position="top"
        :rules="rules"
      >
        <el-form-item label="Adresse email" prop="email">
          <el-input v-model="form.email" placeholder="Email" />
        </el-form-item>
        <el-form-item label="Prénom" prop="first_name">
          <el-input v-model="form.first_name" placeholder="Prénom" />
        </el-form-item>
        <el-form-item label="Nom" prop="last_name">
          <el-input v-model="form.last_name" placeholder="Nom" />
        </el-form-item>
        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="handleSubmit">
            Envoyer l'invitation
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      form: {},
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
        ],
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
      this.$refs["profileForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          this.$store.dispatch('user/invite', this.form)
            .then(response => {
              this.$router.push('/collaborators')
              this.$message({
                message: "L'invitation a bien été envoyé à " + this.form.email,
                type: "success"
              })
              this.loading = false;
            }).catch(error => {
              this.loading = false;
            })
        }
      });
    }
  }
};
</script>
