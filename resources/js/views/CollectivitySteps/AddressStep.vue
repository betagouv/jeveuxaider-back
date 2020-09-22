<template>
  <div class="register-step">
    <portal to="register-steps-help">
      <p>
        Dites nous plus sur votre collectivité.<br />
        Merci de
        <span class="font-bold">compléter l'adresse</span>
        de votre collectivité.
      </p>
      <p>
        Une question? Contactez <br /><span class="font-bold"
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
    <el-steps :active="3" align-center class="p-8 border-b">
      <el-step
        title="Responsable collectivité"
        description="Je complète les informations de mon profil"
      />
      <el-step
        title="Collectivité"
        description="J'enregistre ma collectivité en tant que responsable"
      />
      <el-step
        title="Localisation"
        description="J'enregistre le lieu de ma collectivité"
      />
    </el-steps>
    <div class="max-w-lg p-4 sm:p-12">
      <div class="font-bold text-2xl text-gray-800 mb-6">
        Codes postaux de ma collectivité
      </div>
      <el-form ref="form" :model="form" label-position="top" :rules="rules">
        <el-form-item
          label="Entrez un ou plusieurs codes postaux"
          prop="zips"
          class="flex-1"
        >
          <el-select
            v-model="form.zips"
            multiple
            allow-create
            filterable
            default-first-option
            placeholder="Entrer un code postal..."
          >
          </el-select>
        </el-form-item>

        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Valider
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import { updateCollectivity } from '@/api/collectivity'

export default {
  name: 'CollectivityAddressStep',
  data() {
    return {
      loading: false,
      collectivityId: this.$store.getters.collectivity_as_responsable.id,
      form: this.$store.getters.collectivity_as_responsable,
      rules: {
        zips: {
          required: true,
          message: 'Le code postal est requis',
          trigger: 'blur',
        },
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['form'].validate((valid) => {
        if (valid) {
          updateCollectivity(this.collectivityId, this.form)
            .then(() => {
              this.loading = false
              this.$router.push('/dashboard')
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
