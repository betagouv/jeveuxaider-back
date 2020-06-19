<template>
  <div v-if="$store.getters.profile" class="register-step">
    <portal to="register-steps-help">
      <p>
        Bienvenue {{ $store.getters.user.profile.first_name }} ! <br />Complétez
        votre profil de réserviste afin de mieux cibler
        <span class="font-bold">votre recherche de mission</span>.
      </p>
      <p>
        Une question?
        <br />
        <button onclick="$crisp.push(['do', 'chat:open'])">
          Chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps :active="2" align-center class="p-4 sm:p-8 border-b border-b-2">
      <el-step title="Préférences" description="Je choisis mes préférences" />
      <el-step
        title="Informations"
        description="Je complète mes informations"
      />
    </el-steps>

    <div
      class="flex flex-col items-center lg:items-start lg:flex-row lg:justify-between"
    >
      <div class="p-4 sm:p-12 max-w-2xl order-2">
        <div class="font-bold text-2xl text-gray-800 mb-4">
          Complétez votre profil
        </div>
        <div class="mb-8 text-md text-gray-600">
          Enrichissez votre profil en décrivant vos motivations et en
          renseignant vos disponibilités.
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
          <el-form-item label="Compétences" prop="skills" class="mb-6">
            <el-select
              v-model="form.skills"
              multiple
              filterable
              reserve-keyword
              remote
              :remote-method="fetchSkills"
              placeholder="Ex : peinture en bâtiment, soins infirmiers, service en restauration..."
              :loading="loading"
            >
              <el-option-group
                v-for="(skills, index) in skillGroups"
                :key="index"
                :label="index | labelFromValue('tag_groups')"
              >
                <el-option
                  v-for="item in skills"
                  :key="item.id"
                  :label="item.name.fr"
                  :value="item.name.fr"
                >
                </el-option>
              </el-option-group>
            </el-select>
          </el-form-item>
          <el-form-item
            label="Disponibilités"
            prop="disponibilities"
            class="mb-6"
          >
            <el-select
              v-model="form.disponibilities"
              placeholder="Sélectionnez vos disponibilités"
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
              label="Fréquence"
              prop="frequence"
              class="w-full sm:w-1/2 pr-2"
            >
              <el-select
                v-model="form.frequence"
                placeholder="Sélectionnez votre fréquence"
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
            label="Décrivez vos motivations"
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
        <div class="flex flex-wrap pt-2">
          <router-link :to="{ name: 'PreferencesStep' }" class="mr-2">
            <el-button type="secondary" class="mb-2">
              Retour à l'étape précédente
            </el-button>
          </router-link>
          <el-button
            type="primary"
            class="mb-2"
            :loading="loading"
            @click="onSubmit"
          >
            Enregistrer
          </el-button>
        </div>
      </div>
      <!-- <div
        class="hidden lg:block p-4 mt-8 lg:mt-16 lg:mr-16 order-1 lg:order-3"
      >
        <img
          src="/images/profil.png"
          alt="Préférences"
          style="max-width: 450px;"
        />
      </div> -->
    </div>
  </div>
</template>

<script>
import { uploadImage } from '@/api/app'
import Crop from '@/mixins/Crop'
import { fetchTags } from '@/api/app'
import _ from 'lodash'

export default {
  name: 'InfosStep',
  mixins: [Crop],
  data() {
    return {
      loading: false,
      form: this.$store.getters.user.profile,
      model: 'profile',
      imgMinWidth: 320,
      imgMinHeight: 320,
      imgMaxSize: 2000000, // 2 MB
      rules: {},
      skills: null,
      optionsSkills: [],
    }
  },
  computed: {
    skillGroups() {
      return _.groupBy(this.optionsSkills, (skill) => skill.group)
    },
  },
  created() {
    if (this.form.skills && typeof this.form.skills[0] === 'object') {
      this.form.skills = this.form.skills.map((tag) => tag.name.fr)
    }
  },
  methods: {
    fetchSkills(query) {
      if (query !== '') {
        this.loading = true
        fetchTags({ 'filter[type]': 'competence', 'filter[name]': query }).then(
          (response) => {
            this.loading = false
            this.optionsSkills = response.data.data
          }
        )
      } else {
        this.optionsSkills = []
      }
    },
    onSubmit() {
      this.loading = true
      this.$refs['profileForm'].validate((valid) => {
        if (valid) {
          if (this.img) {
            let cropSettings = this.$refs.cropper
              ? this.$refs.cropper.getData()
              : null
            uploadImage(this.form.id, this.model, this.img, cropSettings).then(
              () => {
                this.updateProfile()
              }
            )
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
        .then((response) => {
          this.loading = false
          this.form = response.data
          this.$router.push('/missions')
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

::v-deep .el-upload-dragger
  @apply flex justify-center items-center
  width: 150px
  height: 150px
</style>
