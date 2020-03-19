<template>
  <div v-if="$store.getters.profile" class="register-step">
    <portal to="register-steps-help">
      <p>
        Bienvenue {{ firstName }} ! <br />Commencez par
        <span class="font-bold">compléter le profil</span> de votre compte Responsable de structure.
      </p>
    </portal>
    <el-steps :active="1" align-center class="p-8 border-b border-b-2">
      <el-step
        title="Profil"
        description="Je complète les informations de mon profil"
      ></el-step>
      <el-step
        title="Structure"
        description="J'enregistre ma structure en tant que responsable"
      ></el-step>
      <el-step
        title="Adresse"
        description="J'enregistre le lieu de mon établissement"
      ></el-step>
    </el-steps>

    <div class="max-w-xl p-12">
      <div class="font-bold text-2xl text-gray-800">
        Mon profil
      </div>
      <div class="flex mt-6 mb-10">
        <div class="flex-1">
          <el-avatar :size="64" class="bg-primary" fit="cover" :src="avatar">
            <span v-if="firstName">{{ firstName[0] }}{{ lastName[0] }}</span>
          </el-avatar>
        </div>
        <!-- <div class="ml-8 mb-auto">
          <el-upload
            action=""
            :http-request="uploadAvatar"
            accept="image/*"
            :before-upload="beforeAvatarUpload"
          >
            <el-button>Modifier</el-button>
            <div slot="tip" class="el-upload__tip text-xs">
              Nous acceptons les fichiers au format PNG, JPG ou GIF, d'une
              taille maximale de 5 Mo
            </div>
          </el-upload>
        </div> -->
      </div>
      <el-form
        ref="profileForm"
        :model="form"
        label-position="top"
        :rules="rules"
      >
        <el-form-item label="Téléphone mobile" prop="mobile">
          <el-input v-model="form.mobile" placeholder="Téléphone mobile" />
        </el-form-item>
        <el-form-item label="Téléphone fixe" prop="phone">
          <el-input v-model="form.phone" placeholder="Téléphone fixe" />
        </el-form-item>
        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Continuer
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
export default {
  name: "ProfileStep",
  data() {
    return {
      loading: false,
      activeName: "profil",
      form: {
        mobile: this.$store.getters.profile.mobile,
        phone: this.$store.getters.profile.phone
      },
      rules: {
        mobile: [
          {
            required: true,
            message: "Un numéro de téléphone est obligatoire",
            trigger: "blur"
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: "Le format du numéro de téléphone est incorrect",
            trigger: "blur"
          }
        ],
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message: "Le format du numéro de téléphone est incorrect",
          trigger: "blur"
        }
      }
    };
  },
  computed: {
    avatar() {
      return this.$store.getters.profile
        ? this.$store.getters.profile.avatar
        : null;
    },
    firstName() {
      return this.$store.getters.profile
        ? this.$store.getters.profile.first_name
        : null;
    },
    lastName() {
      return this.$store.getters.profile
        ? this.$store.getters.profile.last_name
        : null;
    }
  },
  methods: {
    // uploadAvatar(request) {
    //   this.$store.dispatch("user/updateProfileAvatar", {
    //     id: this.$store.getters.profile.id,
    //     avatar: request.file
    //   });
    // },
    // beforeAvatarUpload(file) {
    //   const isLt5M = file.size / 1024 / 1024 < 4;
    //   if (!isLt5M) {
    //     this.$message({
    //       message: "Votre image ne doit pas éxcéder une taille de 4MB",
    //       dangerouslyUseHTMLString: true,
    //       type: "error"
    //     });
    //   }
    //   return isLt5M;
    // },
    onSubmit() {
      this.loading = true;
      this.$refs["profileForm"].validate(valid => {
        if (valid) {
          this.$store
            .dispatch("user/updateProfile", {
              id: this.$store.getters.profile.id,
              ...this.form
            })
            .then(() => {
              this.loading = false;
              this.$router.push("/register/step/structure");
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>
