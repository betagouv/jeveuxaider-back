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
        <span class="font-bold">{{ form.name }}</span
        ><br />
        en images
      </h1>
    </div>
    <div class="rounded-lg bg-white max-w-lg mx-auto overflow-hidden">
      <div
        class="px-8 py-6 bg-white text-black text-3xl font-extrabold leading-9 text-center"
      >
        Faites briller votre organisation
      </div>
      <div class="p-8 bg-gray-50 border-t border-gray-200">
        <el-form
          ref="structureForm"
          :model="form"
          label-position="top"
          class="form-register-steps"
          :rules="rules"
          :hide-required-asterisk="true"
        >
          <el-form-item label="Première image" class="mb-4">
            <img
              :src="`/images/domaines/${selectedImages[0]}.jpg`"
              :srcset="`/images/domaines/${selectedImages[0]}@2x.jpg 2x`"
              class="thumbnail w-64 cursor-pointer"
              @click="onEditImageClick(0)"
            />
          </el-form-item>
          <el-form-item label="Seconde image" class="mb-4">
            <img
              :src="`/images/domaines/${selectedImages[1]}.jpg`"
              :srcset="`/images/domaines/${selectedImages[1]}@2x.jpg 2x`"
              class="thumbnail w-64 cursor-pointer"
              @click="onEditImageClick(1)"
            />
          </el-form-item>
        </el-form>
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
      :domaines="[1, 2, 3]"
      :is-visible="showDialog"
      @picked="onPickedImage"
      @close="showDialog = false"
    />
  </div>
</template>

<script>
export default {
  layout: 'register-steps',
  asyncData({ $api, store }) {
    return {
      structureId: store.getters.structure_as_responsable
        ? store.getters.structure_as_responsable.id
        : null,
      form: store.getters.structure_as_responsable
        ? { ...store.getters.structure_as_responsable }
        : {},
    }
  },
  data() {
    return {
      loading: false,
      imageIndex: 0,
      showDialog: false,
      selectedImages: ['1_1', '2_1'],
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
      rules: {},
    }
  },
  created() {},
  methods: {
    onEditImageClick(index) {
      this.imageIndex = index
      this.showDialog = true
    },
    onPickedImage(imageName) {
      this.selectedImages[this.imageIndex] = imageName
    },
    onSubmit() {
      this.$refs.structureForm.validate((valid) => {
        if (valid) {
          this.loading = true
          this.$api
            .updateStructure(this.structureId, this.form)
            .then(() => {
              this.loading = false
              this.$router.push('/register/responsable/step/over')
            })
            .catch(() => {
              this.loading = false
            })
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped></style>
