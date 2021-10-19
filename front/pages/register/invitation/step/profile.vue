<template>
  <div class="relative">
    <portal to="sidebar"
      ><div class="text-xl lg:text-2xl font-bold mb-6 lg:mb-12">
        Ça ne devrait pas prendre plus de quelques secondes 😉
      </div>
      <Steps :steps="steps"
    /></portal>

    <div class="mb-6 lg:mb-12 text-center text-white">
      <h1 class="text-4xl lg:text-5xl font-medium leading-12 mb-4">
        Bienvenue
        <span class="font-bold">{{ $store.getters.profile.first_name }}</span> !
      </h1>
      <div class="text-lg font-medium">
        Vous avez été invité à rejoindre JeVeuxAider.gouv.fr.
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
                  <div class="text-xs text-[#d2d6dc] uppercase">FACULTATIF</div>
                </template>

                <template
                  slot="button-crop"
                  slot-scope="{ events: { setDialogCropVisible } }"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 m-1 cursor-pointer transition-colors hover:text-[#16a972] focus:text-[#16a972] duration-300 ease-in-out"
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

            <el-form-item label="Profession" prop="type" class="!mb-5">
              <el-select
                v-model="form.type"
                placeholder="Sélectionnez votre profession"
              >
                <el-option
                  v-for="item in $store.getters.taxonomies.profile_types.terms"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>

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
            <el-form-item label="Code postal" prop="zip">
              <input
                v-model="form.zip"
                label="Code postal"
                autocomplete="new-password"
                placeholder="Code postal"
                class="custom-input placeholder-gray-600"
              />
            </el-form-item>
          </div>
        </el-form>
        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="!shadow-lg !block !w-full !text-center !rounded-lg !z-10 !border !border-transparent !bg-[#16a972] !px-4 sm:!px-6 !py-4 !text-lg sm:!text-xl !leading-6 !font-bold !text-white hover:!bg-[#0e9f6e] focus:!outline-none focus:!border-indigo-700 focus:!ring-indigo !transition"
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
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  layout: 'register-steps',
  data() {
    return {
      loading: false,
      activeName: 'profil',
      form: {
        zip: this.$store.getters.profile.zip,
        mobile: this.$store.getters.profile.mobile,
        phone: this.$store.getters.profile.phone,
        image: this.$store.getters.profile.image,
      },
      model: 'profile',
      avatar: null,
      steps: [
        {
          name: 'Rejoignez le mouvement',
          status: 'complete',
          href: '/register/responsable/step/profile',
        },
        {
          name: 'Votre profil',
          status: 'current',
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
        type: [
          {
            required: true,
            message: 'Choisissez votre profession',
            trigger: 'blur',
          },
        ],
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message: 'Le format du numéro de téléphone est incorrect',
          trigger: 'blur',
        },
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
      },
    }
  },
  mounted() {
    document.getElementById('step-container').scrollTop = 0
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.profileForm.validate((valid, fields) => {
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
          this.showErrors(fields)
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
        .then(() => {
          this.loading = false
          this.$router.push('/dashboard')
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>

<style lang="postcss" scoped>
.component--image-field {
  ::v-deep {
    .el-upload-dragger {
      width: inherit;
      height: inherit;
      border: none;
      background: transparent;
    }
    .preview-area {
      height: 100px;
      box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, 0.06);
      @apply rounded-full m-auto overflow-hidden mt-2;
      > img {
        @apply object-cover w-full h-full;
      }
    }
    .actions {
      margin-top: 0.25rem !important;
      @apply flex items-center justify-center mb-6;
    }
  }
}
</style>
