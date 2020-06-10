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
        Complétez votre profil
      </div>
      <div class="mb-8 text-md text-gray-600">
        Enrichissez votre profil en décrivant vos attentes et en reseignant vos
        disponibilités.
      </div>
      <el-form
        ref="profileForm"
        :model="form"
        label-position="top"
        :rules="rules"
        class="max-w-xl"
      >
        <el-form-item
          label="Disponibilités"
          prop="disponibilities"
          class="mb-6"
        >
          <el-select
            v-model="form.disponibilities"
            placeholder="Sélectionner vos disponibilités"
            multiple
          >
            <el-option
              v-for="item in $store.getters.taxonomies.profile_disponibilities
                .terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          label="Description de la mission"
          prop="description"
          class="flex-1"
        >
          <el-input
            v-model="form.description"
            name="description"
            type="textarea"
            :autosize="{ minRows: 4, maxRows: 6 }"
            placeholder="Décrivez-vous de manière succinte"
          ></el-input>
        </el-form-item>
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
  name: 'InfosStep',
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
              this.$router.push('/register/reserviste/step/visibility')
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
