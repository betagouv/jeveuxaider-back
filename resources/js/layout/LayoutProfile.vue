<template>
  <div class="bg-gray-100">
    <AppHeader />
    <div class="bg-primary pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">
            Mon compte
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

          <div
            class="flex flex-col lg:flex-row mt-8 pt-8 border-t border-gray-200"
          >
            <user-menu class="bg-gray-100 lg:bg-transparent mb-4"></user-menu>
            <div
              class="flex-1 px-1 lg:px-12 pt-2 lg:ml-12 lg:border-l lg:border-gray-200 max-w-2xl"
            >
              <router-view></router-view>
            </div>
          </div>
        </div>
      </div>
    </div>
    <AppFooter />
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
