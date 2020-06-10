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

          <el-form
            ref="profileForm"
            :model="form"
            label-position="top"
            :rules="rules"
            :hide-required-asterisk="true"
          >
            <div class="mt-8 border-t border-gray-200 pt-8">
              <div class="flex justify-between">
                <div class="">
                  <h3 class="text-lg leading-tight font-medium text-gray-900">
                    <template v-if="form.is_visible"
                      >Votre profil est visible</template
                    >
                    <template v-else>Votre profil n'est pas visible</template>
                  </h3>
                  <p class="mt-1 text-sm text-gray-500 max-w-xl">
                    En rendant votre profil visible, vous acceptez de recevoir
                    des propositions d'organisations publiques à la recherche de
                    réservistes.
                  </p>
                </div>
                <div class="">
                  <el-switch
                    v-model="form.is_visible"
                    active-color="#1E429F"
                    inactive-color="#959595"
                  >
                  </el-switch>
                </div>
              </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-8">
              <div class="">
                <div class="">
                  <h3 class="text-lg leading-tight font-medium text-gray-900">
                    Photo de profil
                  </h3>
                  <p class="mt-1 mb-8 text-sm text-gray-500 max-w-xl">
                    Mettez votre profil en avant en choisissant une photo de
                    profil.<br />
                    (Taille minimale: 320x320)
                  </p>
                </div>
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
                        imgSrc
                          ? imgSrc
                          : form.image
                          ? form.image.original
                          : null
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
            </div>

            <div class="mt-8 border-t border-gray-200 pt-8">
              <h3 class="text-lg leading-tight font-medium text-gray-900">
                Sélectionner vos domaines
              </h3>
              <p class="mt-1 mb-8 text-sm text-gray-500 max-w-xl">
                Enrichisser votre profil en indiquant aux organisations vos
                domaines d'action de prédilection.
              </p>
              <div v-if="domaines" class="">
                <el-form-item prop="domaines" class="flex-1 max-w-xl">
                  <el-select
                    v-model="form.domaines"
                    multiple
                    filterable
                    placeholder="Sélectionner vos domaines d'action"
                  >
                    <el-option
                      v-for="domaine in domaines"
                      :key="domaine.id"
                      :label="domaine.name.fr"
                      :value="domaine.name.fr"
                    ></el-option>
                  </el-select>
                </el-form-item>
              </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-8">
              <h3 class="text-lg leading-tight font-medium text-gray-900">
                Sélectionner vos compétences
              </h3>
              <p class="mt-1 mb-8 text-sm text-gray-500 max-w-xl">
                Enrichisser votre profil en indiquant aux organisations vos
                compétences.
              </p>
              <div v-if="skills" class="">
                <el-form-item prop="skills" class="flex-1 max-w-xl">
                  <el-select
                    v-model="form.skills"
                    multiple
                    filterable
                    placeholder="Sélectionner vos compétences"
                  >
                    <el-option
                      v-for="skill in skills"
                      :key="skill.id"
                      :label="skill.name.fr"
                      :value="skill.name.fr"
                    ></el-option>
                  </el-select>
                </el-form-item>
              </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-8">
              <h3 class="text-lg leading-tight font-medium text-gray-900">
                Informations personnelles
              </h3>
              <p class="mt-1 mb-8 text-sm text-gray-500">
                Ces informations sont transmises aux structures lorsque vous
                candidatez
              </p>
              <div class="max-w-2xl">
                <el-form-item label="Email" prop="email" class="mb-6">
                  <el-input v-model.trim="form.email" placeholder="Email" />
                </el-form-item>
                <div class="flex mb-4">
                  <el-form-item
                    label="Prénom"
                    prop="first_name"
                    class="flex-1 mr-2"
                  >
                    <el-input v-model="form.first_name" placeholder="Prénom" />
                  </el-form-item>
                  <el-form-item
                    label="Nom"
                    prop="last_name"
                    class="flex-1 ml-2"
                  >
                    <el-input v-model="form.last_name" placeholder="Nom" />
                  </el-form-item>
                </div>
                <div class="flex mb-4">
                  <el-form-item
                    label="Téléphone mobile"
                    prop="mobile"
                    class="flex-1 mr-2"
                  >
                    <el-input
                      v-model="form.mobile"
                      placeholder="Téléphone mobile"
                    />
                  </el-form-item>
                  <el-form-item
                    label="Téléphone fixe"
                    prop="phone"
                    class="flex-1 ml-2"
                  >
                    <el-input
                      v-model="form.phone"
                      placeholder="Téléphone fixe"
                    />
                  </el-form-item>
                </div>
                <div class="flex mb-4">
                  <el-form-item
                    label="Code postal"
                    prop="zip"
                    class="flex-1 mr-2"
                  >
                    <el-input v-model="form.zip" placeholder="Code postal" />
                  </el-form-item>
                  <el-form-item
                    label="Date de naissance"
                    prop="birthday"
                    class="flex-1 ml-2"
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
                      v-for="item in $store.getters.taxonomies
                        .profile_disponibilities.terms"
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
              </div>
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
import { fetchTags } from '@/api/app'

export default {
  name: 'FrontUserProfile',
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
      skills: null,
      domaines: null,
      model: 'profile',
      imgMinWidth: 320,
      imgMinHeight: 320,
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
  created() {
    fetchTags().then((response) => {
      this.skills = response.data.data.filter((tag) => tag.type == 'competence')
      this.domaines = response.data.data.filter((tag) => tag.type == 'domaine')
      this.form.skills = this.form.skills.map((tag) => tag.name.fr)
      this.form.domaines = this.form.domaines.map((tag) => tag.name.fr)
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
            .dispatch('user/updateProfile', this.form)
            .then(() => {
              this.loading = false
              this.$message({
                message: 'Votre profil a été mis à jour.',
                type: 'success',
              })
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
