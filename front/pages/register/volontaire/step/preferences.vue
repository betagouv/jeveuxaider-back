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
    </div>
    <div class="rounded-lg bg-white max-w-lg mx-auto overflow-hidden">
      <div
        class="px-8 py-6 bg-white text-black text-3xl font-extrabold leading-9 text-center"
      >
        Sélectionnez vos préférences
      </div>
      <div class="p-8 bg-gray-50 border-t border-gray-200">
        <div class="mb-8 text-md text-gray-500">
          Enrichissez votre profil avec vos domaines d'action de prédilection
          ainsi que les compétences que vous souhaitez mettre au service des
          organisations publiques ou associatives.
        </div>
        <el-form
          ref="profileForm"
          :model="form"
          label-position="top"
          :rules="rules"
          class="max-w-xl"
        >
          <el-form-item
            label="Domaines d'action"
            prop="domaines"
            class="flex-1 max-w-xl"
          >
            <div class="flex flex-wrap -m-1">
              <div
                v-for="domaine in domaines"
                :key="domaine.id"
                class="px-4 rounded-lg border text-gray-600 bg-white cursor-pointer m-1 transition duration-200 ease-in-out"
                :class="
                  form.domaines.includes(domaine.id)
                    ? 'text-green-400 border-green-400 font-bold'
                    : 'border-gray-200'
                "
                @click="handleClickDomaine(domaine.id)"
              >
                {{ domaine.name.fr }}
              </div>
            </div>
          </el-form-item>

          <div
            class="mb-8 text-black text-2xl font-extrabold leading-9 text-center"
          >
            Visibilité de votre profil
          </div>

          <div class="mb-8 text-md text-gray-500">
            Un profil visible vous offre plus de chances de trouver une mission
            qui répond à votre envie d'engagement, en permettant à une
            organisation publique ou associative de vous contacter en fonction
            des domaines d'action que vous avez sélectionnés.
          </div>

          <fieldset class="mb-8">
            <legend class="sr-only">Visibilité de votre profil</legend>
            <div class="bg-white rounded-md -space-y-px">
              <label
                class="rounded-tl-md rounded-tr-md relative border p-4 flex cursor-pointer"
                :class="
                  !isProfileVisible
                    ? 'bg-blue-50 border-blue-800 z-10'
                    : 'border-gray-200'
                "
              >
                <input
                  type="radio"
                  name="is_visible"
                  :value="false"
                  class="form-radio h-4 w-4 mt-0.5 cursor-pointer text-blue-800 border-gray-300 focus:ring-blue-800"
                  aria-labelledby="privacy-setting-0-label"
                  aria-describedby="privacy-setting-0-description"
                  :checked="!isProfileVisible"
                  @click="form.is_visible = 0"
                />
                <div class="ml-3 flex flex-col flex-1">
                  <span
                    id="privacy-setting-0-label"
                    class="block text-sm font-medium"
                    :class="
                      !isProfileVisible
                        ? 'text-blue-900 font-bold'
                        : 'text-gray-900'
                    "
                  >
                    Profil privé
                  </span>
                  <span
                    id="privacy-setting-0-description"
                    class="block text-sm"
                    :class="
                      !isProfileVisible ? 'text-gray-700' : 'text-gray-500'
                    "
                  >
                    Votre profil ne sera pas visible des organisations.
                  </span>
                </div>
              </label>

              <label
                class="relative rounded-bl-md rounded-br-md border p-4 flex cursor-pointer"
                :class="
                  isProfileVisible
                    ? 'bg-blue-50 border-blue-800 z-10'
                    : 'border-gray-200'
                "
              >
                <input
                  type="radio"
                  name="is_visible"
                  :value="true"
                  class="form-radio h-4 w-4 mt-0.5 cursor-pointer text-blue-800 border-gray-300 focus:ring-blue-800"
                  aria-labelledby="privacy-setting-1-label"
                  aria-describedby="privacy-setting-1-description"
                  :checked="isProfileVisible"
                  @click="form.is_visible = 1"
                />
                <div class="ml-3 flex flex-col flex-1">
                  <span
                    id="privacy-setting-1-label"
                    class="text-gray-900 block text-sm font-medium"
                    :class="
                      isProfileVisible
                        ? 'text-blue-900 font-bold'
                        : 'text-gray-900'
                    "
                  >
                    Profil public
                  </span>
                  <span
                    id="privacy-setting-1-description"
                    class="block text-sm"
                    :class="
                      isProfileVisible ? 'text-gray-700' : 'text-gray-500'
                    "
                  >
                    Votre profil sera visible des organisations.
                  </span>
                </div>
              </label>
            </div>
          </fieldset>
        </el-form>
        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="shadow-lg block w-full text-center rounded-lg z-10 border border-transparent bg-green-400 px-4 sm:px-6 py-4 text-lg sm:text-xl leading-6 font-bold text-white hover:bg-green-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150"
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
  async asyncData({ $api, store }) {
    const tags = await $api.fetchTags({ 'filter[type]': 'domaine' })
    return {
      domaines: tags.data.data,
      form: { ...store.getters.user.profile },
    }
  },
  data() {
    return {
      loading: false,
      form: { ...this.$store.getters.user.profile },
      rules: {
        domaines: {
          required: true,
          message: "Sélectionnez au moins un domaine d'action",
          trigger: 'blur',
        },
      },
      steps: [
        {
          name: 'Rejoignez le mouvement',
          status: 'complete',
          href: '/register/volontaire/step/profile',
        },
        {
          name: 'Votre profil',
          status: 'complete',
          href: '/register/volontaire/step/profile',
        },
        {
          name: 'Vos préférences',
          status: 'current',
        },
        {
          name: 'Vos compétences',
          status: 'upcoming',
        },
      ],
    }
  },
  computed: {
    isProfileVisible() {
      return this.form.is_visible
    },
  },
  methods: {
    handleClickDomaine(id) {
      if (this.form.domaines.includes(id)) {
        this.form.domaines = this.form.domaines.filter((item) => item !== id)
      } else {
        this.$set(this.form, 'domaines', [...this.form.domaines, id])
      }
    },
    onSubmit() {
      this.$refs.profileForm.validate((valid) => {
        if (valid) {
          this.loading = true

          this.$store
            .dispatch('user/updateProfile', {
              id: this.$store.getters.profile.id,
              ...this.form,
            })
            .then(() => {
              this.loading = false
              this.$router.push('/register/volontaire/step/competences')
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
