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
          <div class="cursor-pointer" @click="onUpload()">
            <!-- <ImageField
                  :model="model"
                  :model-id="$store.getters.profile.id"
                  :min-width="320"
                  :min-height="320"
                  :max-size="2000000"
                  :preview-width="'150px'"
                  :field="form.image"
                  label="Photo de profil"
                  @add-or-crop="avatar = $event"
                  @delete="avatar = null"
                ></ImageField> -->
            <img
              src="@/assets/images/logo-placeholder.svg"
              alt="Logo"
              title="Logo"
            />
            <div class="text-xs font-bold text-gray-700 uppercase">
              AJOUTER VOTRE PHOTO
            </div>
            <div class="text-xs text-gray-300 uppercase">FACULTATIF</div>
          </div>
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
            class="w-full h-auto rounded-lg cursor-pointer"
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
            class="w-full h-auto rounded-lg cursor-pointer"
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
    if (!store.getters.structure_as_responsable) {
      return error({ statusCode: 403 })
    }
    const form = { ...store.getters.structure_as_responsable }
    const defaultImages = store.getters.structure_as_responsable.domaines
      ? [
          store.getters.structure_as_responsable.domaines[0].id + '_1',
          store.getters.structure_as_responsable.domaines[0].id + '_2',
        ]
      : ['1_1', '2_1']
    return {
      structureId: store.getters.structure_as_responsable.id,
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
        },
        {
          name: `Votre organisation en image`,
          status: 'current',
        },
      ],
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
    onUpload() {
      alert('Cette fonctionnalité est à venir prochainement !')
    },
    async onSubmit() {
      this.loading = true

      // @TODO: upload logo

      await this.$api.updateStructure(this.structureId, {
        ...this.form,
        image_1: this.selectedImages[0],
        image_2: this.selectedImages[1],
      })
      await this.$store.dispatch('auth/fetchUser')
      this.loading = false

      if (this.form.collectivity) {
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

<style lang="sass" scoped></style>
