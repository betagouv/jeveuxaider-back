<template>
  <div class="register-step">
    <portal to="register-steps-help">
      <p>
        Dites-nous en plus sur votre collectivité !
        <br />Ces
        <span class="font-bold">informations générales</span> permettront au
        service référent de mieux vous connaître.
      </p>
      <p>
        Une question? Contactez<br /><span class="font-bold"
          ><a
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
    <el-steps :active="2" align-center class="p-4 sm:p-8 border-b-2">
      <el-step
        title="Responsable collectivité"
        description="Je complète les informations de mon profil"
      />
      <el-step
        title="Collectivté"
        description="J'enregistre ma collectivité en tant que responsable"
      />
      <el-step
        title="Localisation"
        description="J'enregistre le lieu de mon établissement"
      />
    </el-steps>
    <div class="p-4 sm:p-12">
      <div class="font-bold text-2xl text-gray-800 mb-6">Ma collectivité</div>
      <el-form
        ref="collectivityForm"
        :model="form"
        label-position="top"
        :rules="rules"
        class="max-w-lg"
      >
        <el-form-item label="Nom de votre collectivité" prop="name">
          <el-input
            v-model="form.name"
            placeholder="Nom de votre collectivité"
          />
        </el-form-item>
        <el-form-item
          label="Présentation synthétique de la collectivité"
          prop="description"
          class="flex-1"
        >
          <el-input
            v-model="form.description"
            type="textarea"
            :autosize="{ minRows: 2, maxRows: 6 }"
            placeholder="Décrivez votre collectivité, en quelques mots"
          />
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
import { addOrUpdateCollectivity } from '@/api/collectivity'

export default {
  name: 'CollectivityInfoseStep',
  data() {
    return {
      loading: false,
      collectivityId: null,
      form: {},
      rules: {
        name: {
          required: true,
          message: 'Le nom de votre collectivité est requis',
          trigger: 'blur',
        },
      },
    }
  },
  created() {
    this.collectivityId = this.$store.getters.collectivity_as_responsable
      ? this.$store.getters.collectivity_as_responsable.id
      : null
    this.form = this.$store.getters.collectivity_as_responsable || {}
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['collectivityForm'].validate((valid) => {
        if (valid) {
          addOrUpdateCollectivity(this.collectivityId, this.form)
            .then(async (response) => {
              this.collectivityId = response.data.id
              // Get profile to get new role
              await this.$store.dispatch('user/get')
              this.$router.push('/register/collectivity/step/address')
              this.loading = false
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
::v-deep .el-upload-list__item-name
    @apply hidden
</style>
