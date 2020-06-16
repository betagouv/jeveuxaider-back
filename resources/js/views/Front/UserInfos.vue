<template>
  <div class="">
    <div class="font-bold text-2xl text-gray-800 mb-4">
      Informations personnelles
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
      :hide-required-asterisk="true"
    >
      <div class="mb-8">
        <el-form-item label="Photo de profil" class="">
          <div v-show="imgPreview">
            <div class="preview-area" style="width: 150px; height: 150px;">
              <img class="rounded" :src="imgPreview" alt="Cropped Image" />
            </div>
            <div class="actions mt-4">
              <el-button
                class="hidden lg:inline"
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
                :src="imgSrc ? imgSrc : form.image ? form.image.original : null"
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
      <el-form-item label="Email" prop="email" class="mb-6">
        <el-input v-model.trim="form.email" placeholder="Email" />
      </el-form-item>
      <div class="flex mb-4">
        <el-form-item label="Prénom" prop="first_name" class="flex-1 mr-2">
          <el-input v-model="form.first_name" placeholder="Prénom" />
        </el-form-item>
        <el-form-item label="Nom" prop="last_name" class="flex-1 ml-2">
          <el-input v-model="form.last_name" placeholder="Nom" />
        </el-form-item>
      </div>
      <div class="flex mb-4">
        <el-form-item
          label="Téléphone mobile"
          prop="mobile"
          class="flex-1 mr-2"
        >
          <el-input v-model="form.mobile" placeholder="Téléphone mobile" />
        </el-form-item>
        <el-form-item label="Téléphone fixe" prop="phone" class="flex-1 ml-2">
          <el-input v-model="form.phone" placeholder="Téléphone fixe" />
        </el-form-item>
      </div>
      <div class="flex mb-4">
        <el-form-item label="Code postal" prop="zip" class="flex-1 mr-2">
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

      <el-form-item label="Disponibilités" prop="disponibilities" class="mb-6">
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
              v-for="item in $store.getters.taxonomies.profile_frequences.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item prop="frequence_granularite" class="w-full sm:w-1/2 pl-2">
          <el-select v-model="form.frequence_granularite" placeholder="Par...">
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
        class="flex-1 mt-4"
      >
        <el-input
          v-model="form.description"
          name="description"
          type="textarea"
          :autosize="{ minRows: 4, maxRows: 6 }"
          placeholder="Décrivez-vous de manière succinte"
        ></el-input>
      </el-form-item>

      <div class="mt-8">
        <el-button type="primary" :loading="loading" class="" @click="onSubmit">
          Enregistrer les modifications
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { uploadImage } from '@/api/app'
import Crop from '@/mixins/Crop'
import UserMenu from '@/components/UserMenu'

export default {
  name: 'FrontUserInfos',
  components: { UserMenu },
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
  created() {},
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
                message: 'Vos informations ont été mises à jour.',
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

::v-deep .el-upload-dragger
  @apply flex justify-center items-center
  width: 150px
  height: 150px
</style>
