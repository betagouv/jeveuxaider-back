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
      <!-- <el-step
        title="Visibilité"
        description="Je rends mon profil visible des organisations publiques ou associatives"
      /> -->
    </el-steps>

    <div class="p-4 sm:p-12 max-w-2xl">
      <div class="font-bold text-2xl text-gray-800 mb-4">
        Complétez votre profil
      </div>
      <div class="mb-8 text-md text-gray-600">
        Enrichissez votre profil en décrivant vos attentes et en renseignant vos
        disponibilités.
      </div>
      <el-form
        ref="profileForm"
        :model="form"
        label-position="top"
        :rules="rules"
        class="max-w-xl"
      >
        <div class="my-8">
          <el-form-item label="Photo de profil" class="">
            <div v-show="imgPreview">
              <div class="preview-area" style="width: 150px; height: 150px;">
                <img class="rounded" :src="imgPreview" alt="Cropped Image" />
              </div>
              <div class="actions mt-4">
                <el-button
                  type="secondary"
                  @click.prevent="dialogCropVisible = true"
                >
                  Recadrer
                </el-button>
                <el-button
                  type="danger"
                  icon="el-icon-delete"
                  :loading="loadingDelete"
                  @click.prevent="onDelete()"
                >
                  Supprimer
                </el-button>
              </div>

              <el-dialog
                title="Recadrer"
                :visible.sync="dialogCropVisible"
                width="680"
              >
                <vue-cropper
                  ref="cropper"
                  :src="
                    imgSrc ? imgSrc : form.image ? form.image.original : null
                  "
                  :aspect-ratio="1 / 1"
                  :zoomable="false"
                  :movable="false"
                  :zoom-on-touch="false"
                  :zoom-on-wheel="false"
                  :auto-crop-area="1"
                  :min-container-height="320"
                  :min-container-width="320"
                  preview=".preview"
                  @cropmove="ensureMinWidth"
                />
                <span slot="footer" class="dialog-footer">
                  <el-button @click="onReset()">Réinitialiser</el-button>
                  <el-button @click="dialogCropVisible = false"
                    >Annuler</el-button
                  >
                  <el-button
                    type="primary"
                    :loading="loadingCrop"
                    @click="onCrop()"
                    >Valider</el-button
                  >
                </span>
              </el-dialog>
            </div>
            <div v-show="!imgPreview">
              <el-upload
                drag
                action=""
                :show-file-list="false"
                :auto-upload="false"
                :on-change="onSelectFile"
              >
                <font-awesome-icon
                  icon="user-astronaut"
                  class="text-gray-400 hover:text-primary"
                  style="width: 100px; height: 100px;"
                />
              </el-upload>
            </div>
          </el-form-item>
        </div>
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
        <div class="flex items-end">
          <el-form-item
            label="Fréquences"
            prop="disponibilities"
            class="w-full sm:w-1/2 pr-2"
          >
            <el-select
              v-model="form.frequence"
              placeholder="Sélectionner votre fréquence"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.profile_frequences
                  .terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            prop="frequence_granularite"
            class="w-full sm:w-1/2 pl-2"
          >
            <el-select
              v-model="form.frequence_granularite"
              placeholder="Par..."
            >
              <el-option
                v-for="item in $store.getters.taxonomies
                  .profile_frequences_granularite.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </div>
        <el-form-item
          label="Descrivez vos motivations"
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
        <router-link :to="{ name: 'PreferencesStep' }" class="mr-2">
          <el-button type="secondary">
            Retour à l'étape précédente
          </el-button>
        </router-link>
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Trouver des missions
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
import { uploadImage } from '@/api/app'
import Crop from '@/mixins/Crop'
import { getProfile } from '@/api/user'

export default {
  name: 'InfosStep',
  mixins: [Crop],
  data() {
    return {
      loading: false,
      form: {},
      model: 'profile',
      imgMinWidth: 320,
      imgMinHeight: 320,
      imgMaxSize: 2000000, // 2 MB
      rules: {},
    }
  },
  created() {
    getProfile(this.$store.getters.user.profile.id)
      .then((response) => {
        this.form = response.data
      })
      .catch(() => {
        this.loading = false
      })
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['profileForm'].validate((valid) => {
        if (valid) {
          if (this.img) {
            let cropSettings = this.$refs.cropper
              ? this.$refs.cropper.getData()
              : null
            uploadImage(this.form.id, this.model, this.img, cropSettings)
          }
          this.$store
            .dispatch('user/updateProfile', {
              id: this.$store.getters.profile.id,
              ...this.form,
            })
            .then((response) => {
              this.loading = false
              this.form = response.data
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

::v-deep .el-upload-dragger
  @apply flex justify-center items-center
  width: 150px
  height: 150px
</style>
