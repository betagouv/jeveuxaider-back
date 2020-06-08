<template>
  <div class="bg-gray-100">
    <AppHeader />
    <div class="bg-primary pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">
            Mon profil
          </h1>
        </div>
      </div>
    </div>
    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <h2 class="text-3xl leading-tight font-extrabold text-gray-900">
            {{ $store.getters.user.profile.first_name }}
            {{ $store.getters.user.profile.last_name }}
          </h2>

          <div class="mt-8 border-t border-gray-200 pt-8">
            <h3 class="text-lg leading-tight font-medium text-gray-900">
              Photo de profil
            </h3>
            <p class="mt-1 mb-8 text-sm text-gray-500">
              Mettez votre profil en avant en choisissant une photo de profil.
              (Taille minimale: 240x240)
            </p>
            <div v-show="imgPreview">
              <div class="preview-area">
                <img :src="imgPreview" alt="Cropped Image" />
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
                  :min-container-height="240"
                  :min-container-width="240"
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
                class="upload-demo"
                drag
                action=""
                :show-file-list="false"
                :auto-upload="false"
                :on-change="onSelectFile"
              >
                <i class="el-icon-upload" />
                <div class="el-upload__text">
                  Glissez votre image ou <br /><em
                    >cliquez ici pour la sélectionner</em
                  >
                </div>
              </el-upload>
            </div>
          </div>

          <el-form
            ref="profileForm"
            :model="form"
            label-position="top"
            :rules="rules"
            :hide-required-asterisk="true"
          >
            <div class="mt-8 border-t border-gray-200 pt-8">
              <h3 class="text-lg leading-tight font-medium text-gray-900">
                Informations personnelles
              </h3>
              <p class="mt-1 mb-8 text-sm text-gray-500">
                Ces informations sont transmises aux structures lorsque vous
                candidatez
              </p>
              <div class="flex flex-wrap -m-2">
                <el-form-item
                  label="Prénom"
                  prop="first_name"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input v-model="form.first_name" placeholder="Prénom" />
                </el-form-item>
                <el-form-item
                  label="Nom"
                  prop="last_name"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input v-model="form.last_name" placeholder="Nom" />
                </el-form-item>
                <el-form-item
                  label="Code postal"
                  prop="zip"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input v-model="form.zip" placeholder="Code postal" />
                </el-form-item>
                <el-form-item
                  label="Date de naissance"
                  prop="birthday"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-date-picker
                    v-model="form.birthday"
                    type="date"
                    placeholder="Date de naissance"
                    autocomplete="off"
                    format="dd-MM-yyyy"
                    value-format="yyyy-MM-dd"
                    style="width: 100%;"
                  />
                </el-form-item>
                <el-form-item
                  label="E-mail"
                  prop="email"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input v-model.trim="form.email" placeholder="E-mail" />
                </el-form-item>
                <el-form-item
                  label="Téléphone mobile"
                  prop="mobile"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input
                    v-model="form.mobile"
                    placeholder="Téléphone mobile"
                  />
                </el-form-item>
              </div>
              <div class="mt-8 border-t border-gray-200 pt-5">
                <el-button
                  type="primary"
                  :loading="loading"
                  class=""
                  @click="onSubmit"
                >
                  Enregistrer les modifications
                </el-button>
              </div>
            </div>
          </el-form>
        </div>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script>
import { uploadImage } from '@/api/app'
import Crop from '@/mixins/Crop'

export default {
  name: 'FrontProfile',
  mixins: [Crop],
  data() {
    var checkLowercase = (rule, value, callback) => {
      if (value !== value.toLowerCase()) {
        callback(new Error('Merci de ne saisir que des minuscules'))
      } else {
        callback()
      }
    }
    return {
      loading: false,
      form: this.$store.getters.user.profile,
      model: 'profile',
      imgMinWidth: 240,
      imgMinHeight: 240,
      imgMaxSize: 2000000, // 2 MB
      rules: {
        email: [
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
          {
            required: true,
            message: 'Veuillez renseigner votre email',
            trigger: 'blur',
          },
          { validator: checkLowercase, trigger: 'blur' },
        ],
        first_name: [
          {
            required: true,
            message: 'Prénom obligatoire',
            trigger: 'blur',
          },
        ],
        last_name: [
          {
            required: true,
            message: 'Nom obligatoire',
            trigger: 'blur',
          },
        ],
        zip: [
          {
            required: true,
            message: 'Code postal obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^\d+$/,
            message: 'Ne doit contenir que des chiffres',
            trigger: 'blur',
          },
          {
            min: 5,
            max: 5,
            message: 'Format erroné',
            trigger: 'blur',
          },
        ],
        mobile: [
          {
            required: true,
            message: 'Téléphone obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['profileForm'].validate((valid) => {
        if (valid) {
          this.$store
            .dispatch('user/updateProfile', this.form)
            .then(() => {
              if (this.img) {
                let cropSettings = this.$refs.cropper
                  ? this.$refs.cropper.getData()
                  : null
                uploadImage(
                  this.form.id,
                  this.model,
                  this.img,
                  cropSettings
                ).then(() => {
                  this.loading = false
                  this.$message({
                    message: 'Votre profil a été mis à jour.',
                    type: 'success',
                  })
                })
              } else {
                this.loading = false
                this.$message({
                  message: 'Votre profil a été mis à jour.',
                  type: 'success',
                })
              }
            })
            .catch(() => {
              this.loading = false
            })
          this.loading = false
        } else {
          this.loading = false
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-form-item
    @apply mb-3

.preview-area
  width: 150px
</style>
