<template>
  <div v-if="$store.getters.profile" class="register-step">
    <portal to="register-steps-help">
      <p>
        Bienvenue {{ $store.getters.user.profile.first_name }} ! <br />Complétez
        votre profil de réservistes afin de mieux cibler
        <span class="font-bold">vos attentes</span>.
      </p>
      <p>
        Une question? Appelez-nous au
        <br />
        <span class="font-bold">
          <a href="tel:0184800189">01 84 80 01 89</a>
        </span>
        ou
        <button onclick="$crisp.push(['do', 'chat:open'])">
          chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps :active="2" align-center class="p-4 sm:p-8 border-b border-b-2">
      <el-step title="Préférences" description="Je choisis mes préférences" />
      <el-step
        title="Informations"
        description="Je complète mes informations"
      />
      <el-step
        title="Visibilité"
        description="Je rends mon profil visible des organisations publiques"
      />
    </el-steps>

    <div class="p-4 sm:p-12 max-w-2xl">
      <div class="font-bold text-2xl text-gray-800 mb-4">
        Visibilité de votre profil
      </div>
      <div class="mb-8 text-md text-gray-600">
        En rendant votre profil visible, vous acceptez de recevoir des
        propositions d'organisations publiques à la recherche de réservistes.
      </div>
      <el-form
        ref="profileForm"
        :model="form"
        label-position="top"
        :rules="rules"
        class="max-w-xl"
      >
        <el-form-item
          :label="
            form.is_visible
              ? 'Votre profil est visible'
              : 'Votre profil n\'est pas visible'
          "
          prop="is_visible"
          class="mb-6"
        >
          <el-switch
            v-model="form.is_visible"
            active-color="#1E429F"
            inactive-color="#959595"
          ></el-switch>
        </el-form-item>
      </el-form>
      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Trouver des missions
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VisbilityStep',
  data() {
    return {
      loading: false,
      form: this.$store.getters.user.profile,
      rules: {},
    }
  },
  created() {
    delete this.form.skills
    delete this.form.domaines
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['profileForm'].validate((valid) => {
        if (valid) {
          this.$store
            .dispatch('user/updateProfile', {
              id: this.$store.getters.profile.id,
              ...this.form,
            })
            .then(() => {
              this.loading = false
              this.$router.push('/missions')
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.loading = false
        }
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
