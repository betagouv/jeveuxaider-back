<template>
  <div class="relative">
    <portal to="sidebar"
      ><div
        class="text-xl lg:text-2xl font-bold mb-6 lg:mb-12 text-center sm:text-left"
      >
        Ça ne devrait pas prendre plus de 3 minutes 😉
      </div>
      <Steps :steps="steps"
    /></portal>
    <div class="mb-6 lg:mb-12 text-center text-white">
      <h1 class="text-4xl lg:text-5xl font-medium leading-12 mb-4">
        <span class="font-bold">{{ form.name }}</span
        ><br />
        en images
      </h1>
    </div>
    <div class="rounded-lg bg-white max-w-xl mx-auto overflow-hidden">
      <div
        class="px-8 pt-6 pb-20 bg-white text-black text-3xl font-extrabold leading-9 text-center"
      >
        Faites briller votre organisation
      </div>
      <div class="p-8 bg-gray-50 border-t border-gray-200">
        <div
          class="flex flex-col items-center text-center mb-8"
          style="margin-top: -100px"
        >
          <ImageField
            model="structure"
            :model-id="form.id"
            :max-size="2000000"
            :preview-width="'200px'"
            :field="form.logo"
            :aspect-ratio="0"
            label="Logo"
            field-name="logo"
            :min-width="120"
            @add-or-crop="logo = $event"
            @delete="logo = null"
          >
            <div slot="label"></div>
            <div slot="description"></div>

            <template slot="dragZone">
              <img
                src="@/assets/images/logo-placeholder.svg"
                alt="Logo"
                title="Logo"
                class="m-auto"
              />
              <div class="text-xs font-bold text-gray-700 uppercase">
                AJOUTER VOTRE LOGO
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

        <template v-if="form.statut_juridique == 'Association'">
          <div
            class="mb-8 text-black text-2xl font-extrabold leading-9 text-center"
          >
            Choisissez 2 visuels pour illustrer l'activité de votre
            organisation.
          </div>
          <div class="relative mb-8" @click="onEditImageClick(0)">
            <div
              class="text-center text-gray-400 font-semibold text-sm uppercase mb-4"
            >
              Visuel N°1
            </div>
            <img
              :src="`/images/organisations/domaines/${selectedImages[0]}.jpg`"
              :srcset="`/images/organisations/domaines/${selectedImages[0]}@2x.jpg 2x`"
              class="w-full h-auto rounded-lg cursor-pointer shadow-xl"
            />
            <div
              class="z-1 absolute flex justify-center items-center w-8 h-8 text-blue-800 bg-white rounded-full opacity-75 hover:opacity-100 cursor-pointer"
              style="right: 12px; bottom: 12px"
            >
              <div
                class="text-blue-800"
                v-html="
                  require('@/assets/images/icones/heroicon/edit.svg?include')
                "
              />
            </div>
          </div>

          <div class="relative mb-8" @click="onEditImageClick(1)">
            <div
              class="text-center text-gray-400 font-semibold text-sm uppercase mb-4"
            >
              Visuel N°2
            </div>
            <img
              :src="`/images/organisations/domaines/${selectedImages[1]}.jpg`"
              :srcset="`/images/organisations/domaines/${selectedImages[1]}@2x.jpg 2x`"
              class="w-full h-auto rounded-lg cursor-pointer shadow-xl"
            />
            <div
              class="z-1 absolute flex justify-center items-center w-8 h-8 text-blue-800 bg-white rounded-full opacity-75 hover:opacity-100 cursor-pointer"
              style="right: 12px; bottom: 12px"
            >
              <div
                class="text-blue-800"
                v-html="
                  require('@/assets/images/icones/heroicon/edit.svg?include')
                "
              />
            </div>
          </div>
        </template>

        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="shadow-lg block w-full text-center rounded-lg z-10 border border-transparent bg-green-400 px-4 sm:px-6 py-4 text-lg sm:text-xl leading-6 font-bold text-white hover:bg-green-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150"
              @click="onSubmit"
              >Terminer</el-button
            >
          </span>
        </div>
      </div>
    </div>
    <DialogOrganisationImagesPicker
      :initial-image="selectedImages[imageIndex]"
      :domaines="form.domaines"
      :is-visible="showDialog"
      @picked="onPickedImage"
      @close="showDialog = false"
    />
  </div>
</template>

<script>
export default {
  layout: 'register-steps',
  asyncData({ $api, store, error }) {
    if (!store.getters.structure) {
      return error({ statusCode: 403 })
    }
    const form = { ...store.getters.structure }
    const defaultImages =
      store.getters.structure.domaines.length > 0
        ? [
            store.getters.structure.domaines[0].id + '_1',
            store.getters.structure.domaines[0].id + '_2',
          ]
        : ['1_1', '2_1']
    return {
      structureId: store.getters.structure.id,
      form,
      selectedImages: form.image_1
        ? [form.image_1, form.image_2]
        : defaultImages,
    }
  },
  data() {
    return {
      loading: false,
      imageIndex: 0,
      showDialog: false,
      steps: [
        {
          name: 'Rejoignez le mouvement',
          status: 'complete',
          href: '/register/responsable/step/profile',
        },
        {
          name: 'Votre profil',
          status: 'complete',
          href: '/register/responsable/step/profile',
        },
        {
          name: `Informations sur l'organisation`,
          status: 'complete',
          href: '/register/responsable/step/structure',
        },
        {
          name: `Quelques mots sur l'organisation`,
          status: 'complete',
          href: '/register/responsable/step/infos',
          disable:
            this.$store.getters.structure.statut_juridique == 'Collectivité',
        },
        {
          name: `Votre organisation en images`,
          status: 'current',
        },
      ],
      logo: null,
    }
  },
  created() {},
  mounted() {
    document.getElementById('step-container').scrollTop = 0
  },
  methods: {
    onEditImageClick(index) {
      this.imageIndex = index
      this.showDialog = true
    },
    onPickedImage(imageName) {
      this.selectedImages[this.imageIndex] = imageName
    },
    async onSubmit() {
      this.loading = true

      if (this.logo) {
        await this.$api.uploadImage(
          this.form.id,
          'structure',
          this.logo.blob,
          this.logo.cropSettings,
          'logo'
        )
        if (this.form.territoire) {
          await this.$api.uploadImage(
            this.form.territoire.id,
            'territoire',
            this.logo.blob,
            this.logo.cropSettings,
            'logo'
          )
        }
      }

      await this.$api.updateStructure(this.structureId, {
        ...this.form,
        image_1: this.selectedImages[0],
        image_2: this.selectedImages[1],
      })
      await this.$store.dispatch('auth/fetchUser')
      this.loading = false

      window.plausible &&
        window.plausible(
          'Inscription responsable - Étape 5 - Votre organisation en images'
        )

      if (this.form.territoire) {
        this.$router.push(
          '/register/responsable/step/confirmation-collectivite'
        )
      } else {
        this.$router.push(
          '/register/responsable/step/confirmation-organisation'
        )
      }
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
      border-radius: 1rem
      @apply bg-white m-auto overflow-hidden mt-2
      > img
        @apply object-contain w-full h-full
    .actions
      margin-top: .25rem !important
      @apply flex items-center justify-center mb-6
</style>
