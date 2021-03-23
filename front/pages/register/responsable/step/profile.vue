<template>
  <div v-if="$store.getters.profile" class="register-step">
    <portal to="register-steps-help">
      <p>
        Bienvenue {{ firstName }} ! <br />Commencez par
        <span class="font-bold">compléter le profil</span> de votre compte
        Responsable de l'organisation.
      </p>
      <p>
        Une question? Contactez
        <br />
        <span class="font-bold">
          <a
            target="_blank"
            href="mailto:contact@reserve-civique.on.crisp.email"
          >
            le support</a
          >
        </span>
        ou
        <button onclick="$crisp.push(['do', 'chat:open'])">
          chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps :active="1" align-center class="p-4 sm:p-8 border-b-2">
      <el-step
        title="Profil"
        description="Je complète les informations de mon profil"
      />
      <el-step
        title="Organisation"
        description="J'enregistre mon organisation en tant que responsable"
      />
      <el-step
        title="Adresse"
        description="J'enregistre le lieu de mon établissement"
      />
    </el-steps>

    <div class="p-4 sm:p-12">
      <div class="font-bold text-2xl text-gray-800">Mon profil</div>

      <el-form
        ref="profileForm"
        :model="form"
        label-position="top"
        :rules="rules"
      >
        <div class="my-8">
          <ImageField
            :model="model"
            :model-id="$store.getters.profile.id"
            :min-width="320"
            :min-height="320"
            :max-size="2000000"
            :preview-width="'150px'"
            :field="form.image"
            label="Photo de profil"
            @add-or-crop="avatar = $event"
            @delete="avatar = null"
          ></ImageField>
        </div>

        <div class="flex flex-wrap -m-2">
          <el-form-item
            label="Téléphone mobile"
            prop="mobile"
            class="w-full sm:w-1/2 lg:w-1/3 p-2"
          >
            <el-input v-model="form.mobile" placeholder="Téléphone mobile" />
          </el-form-item>
          <el-form-item
            label="Téléphone fixe"
            prop="phone"
            class="w-full sm:w-1/2 lg:w-1/3 p-2"
          >
            <el-input v-model="form.phone" placeholder="Téléphone fixe" />
          </el-form-item>
        </div>
      </el-form>
      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Continuer
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'register-steps',
  data() {
    return {
      loading: false,
      activeName: 'profil',
      form: {
        mobile: this.$store.getters.profile.mobile,
        phone: this.$store.getters.profile.phone,
        image: this.$store.getters.profile.image,
      },
      model: 'profile',
      avatar: null,
      rules: {
        mobile: [
          {
            required: true,
            message: 'Un numéro de téléphone est obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
            trigger: 'blur',
          },
        ],
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message: 'Le format du numéro de téléphone est incorrect',
          trigger: 'blur',
        },
      },
    }
  },
  computed: {
    firstName() {
      return this.$store.getters.profile
        ? this.$store.getters.profile.first_name
        : null
    },
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.profileForm.validate((valid) => {
        if (valid) {
          if (this.avatar) {
            this.$api
              .uploadImage(
                this.$store.getters.profile.id,
                this.model,
                this.avatar.blob,
                this.avatar.cropSettings
              )
              .then(() => {
                this.updateProfile()
              })
          } else {
            this.updateProfile()
          }
        } else {
          this.loading = false
        }
      })
    },
    updateProfile() {
      this.$store
        .dispatch('user/updateProfile', {
          id: this.$store.getters.profile.id,
          ...this.form,
        })
        .then(() => {
          this.loading = false
          this.$router.push('/register/responsable/step/structure')
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-step__description
  @apply hidden
    @screen sm
      @apply block
</style>
