<template>
  <div class="relative">
    <portal to="sidebar"
      ><div class="text-xl lg:text-2xl font-bold mb-6 lg:mb-12">
        Ça ne devrait pas prendre plus de 3 minutes 😉
      </div>
      <Steps :steps="steps"
    /></portal>

    <div class="mb-6 lg:mb-12 text-center text-white">
      <h1 class="text-4xl lg:text-5xl font-medium leading-12 mb-4">
        Bienvenue
        <span class="font-bold">{{ $store.getters.profile.first_name }}</span> !
      </h1>
      <div class="text-lg font-medium">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis
        voluptatibus doloremque praesentium.
      </div>
    </div>
    <div class="rounded-lg bg-white max-w-xl mx-auto overflow-hidden">
      <div
        class="px-8 pt-6 pb-20 bg-white text-black text-3xl font-extrabold leading-9 text-center"
      >
        Complétez votre profil
      </div>
      <div class="p-8 bg-gray-50 border-t border-gray-200">
        <el-form
          ref="profileForm"
          :model="form"
          class="form-register-steps"
          label-position="top"
          :rules="rules"
          :hide-required-asterisk="true"
        >
          <div class="">
            <div
              class="flex flex-col items-center text-center mb-3"
              style="margin-top: -110px"
            >
              <ImageField
                :model="model"
                :model-id="$store.getters.profile.id"
                :min-width="320"
                :min-height="320"
                :max-size="2000000"
                :preview-width="'100px'"
                :field="form.image"
                label="Photo de profil"
                @add-or-crop="avatar = $event"
                @delete="avatar = null"
              >
                <div slot="label"></div>
                <div slot="description"></div>

                <template slot="dragZone">
                  <img
                    src="@/assets/images/picture-placeholder.svg"
                    alt="Photo"
                    title="Photo"
                    class="m-auto"
                  />
                  <div class="text-xs font-bold text-gray-700 uppercase">
                    AJOUTER UNE PHOTO
                  </div>
                  <div class="text-xs text-gray-300 uppercase">FACULTATIF</div>
                </template>

                <template
                  slot="button-crop"
                  slot-scope="{ events: { setDialogCropVisible } }"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 m-1 cursor-pointer transition-colors hover:text-green-400 focus:text-green-400 duration-300 ease-in-out"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    @click="setDialogCropVisible(true)"
                  >
                    <path
                      d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"
                    />
                  </svg>
                </template>

                <template
                  slot="button-delete"
                  slot-scope="{ events: { onDelete } }"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 m-1 cursor-pointer transition-colors hover:text-red-700 focus:text-red-700 duration-300 ease-in-out"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    @click.prevent="onDelete()"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </template>
              </ImageField>
            </div>

            <el-form-item label="Téléphone mobile" prop="mobile" class="mb-5">
              <input
                v-model="form.mobile"
                placeholder="Téléphone mobile"
                class="custom-input placeholder-gray-600"
              />
            </el-form-item>
            <el-form-item label="Téléphone fixe" prop="phone" class="mb-5">
              <input
                v-model="form.phone"
                placeholder="Téléphone fixe"
                class="custom-input placeholder-gray-600"
              />
            </el-form-item>
            <el-form-item
              label="Disponibilités"
              prop="disponibilities"
              class="flex-1 max-w-xl"
            >
              <el-checkbox-group
                v-model="form.disponibilities"
                size="medium"
                class="custom-checkbox"
              >
                <el-checkbox
                  v-for="item in $store.getters.taxonomies
                    .profile_disponibilities.terms"
                  :key="item.value"
                  :label="item.label"
                  class="bg-white"
                  border
                ></el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <div class="flex items-end">
              <el-form-item
                label="Fréquence"
                prop="frequence"
                class="w-full sm:w-2/3 pr-2"
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
                class="w-full sm:w-1/3 pl-2"
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
              <textarea
                v-model="form.description"
                rows="4"
                class="custom-textarea placeholder-gray-600"
                placeholder="Décrivez-vous de manière succinte"
              />
            </el-form-item>
          </div>
        </el-form>
        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="shadow-lg block w-full text-center rounded-lg z-10 border border-transparent bg-green-400 px-4 sm:px-6 py-4 text-lg sm:text-xl leading-6 font-bold text-white hover:bg-green-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150"
              @click="onSubmit"
              >Continuer</el-button
            >
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'register-steps',
  asyncData({ $api, store }) {
    return {
      form: {
        ...store.getters.profile,
        disponibilities: store.getters.profile.disponibilities
          ? store.getters.profile.disponibilities
          : [],
      },
    }
  },
  data() {
    return {
      loading: false,
      model: 'profile',
      avatar: null,
      steps: [
        {
          name: 'Rejoignez le mouvement',
          status: 'complete',
          href: '/register/volontaire/step/profile',
        },
        {
          name: 'Votre profil',
          status: 'current',
        },
        {
          name: 'Vos préférences',
          status: 'upcoming',
        },
        {
          name: 'Vos compétences',
          status: 'upcoming',
        },
      ],
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
  mounted() {
    document.getElementById('step-container').scrollTop = 0
  },
  methods: {
    onUpload() {
      alert('Cette fonctionnalité est à venir prochainement !')
    },
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
    async updateProfile() {
      await this.$store.dispatch('user/updateProfile', {
        id: this.$store.getters.profile.id,
        ...this.form,
      })
      this.loading = false
      this.$router.push('/register/volontaire/step/preferences')
    },
  },
}
</script>

<style lang="sass" scoped>
.component--image-field
  ::v-deep
    .el-upload-dragger
      width: inherit
      height: inherit
      border: none
      background: transparent
    .preview-area
      height: 100px
      box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, .06)
      @apply rounded-full m-auto overflow-hidden mt-2
      > img
        @apply object-cover w-full h-full
    .actions
      margin-top: .25rem !important
      @apply flex items-center justify-center mb-6
</style>
