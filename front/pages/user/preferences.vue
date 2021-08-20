<template>
  <div class="">
    <div class="font-bold text-[1.75rem] text-[#242526] mb-4">Préférences</div>

    <div class="mb-8 text-md text-gray-600">
      Enrichissez votre profil avec vos domaines d'action de prédilection ainsi
      que les compétences que vous souhaitez mettre au service des organisations
      publiques ou associatives.
    </div>

    <el-form
      ref="profileForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
    >
      <el-form-item label="Domaines d'action" prop="domaines">
        <el-checkbox-group
          v-model="domainesSelected"
          size="medium"
          class="custom-checkbox"
        >
          <el-checkbox
            v-for="domaine in domaines"
            :key="domaine.id"
            :label="domaine.name.fr"
            class="!bg-white"
            border
            :checked="isDomaineSelected(domaine.id)"
            @change="handleClickDomaine(domaine)"
          ></el-checkbox>
        </el-checkbox-group>
      </el-form-item>

      <div class="font-bold text-[1.75rem] text-[#242526] mb-4 mt-12">
        Visibilité de votre profil
      </div>

      <div class="mb-8 text-md text-gray-600">
        Un profil visible vous offre plus de chances de trouver une mission qui
        répond à votre envie d'engagement, en permettant à une organisation
        publique ou associative de vous contacter en fonction des domaines
        d'action que vous avez sélectionnés.
      </div>
      <fieldset class="mb-8">
        <legend class="sr-only">Visibilité de votre profil</legend>
        <div class="bg-white rounded-md -space-y-px">
          <label
            class="rounded-tl-md rounded-tr-md relative border p-4 flex cursor-pointer"
            :class="
              !isProfileVisible
                ? 'bg-blue-50 border-[#070191] z-10'
                : 'border-gray-200'
            "
          >
            <input
              type="radio"
              name="is_visible"
              :value="false"
              class="form-radio h-4 w-4 mt-0.5 cursor-pointer text-[#070191] border-[#d2d6dc] focus:ring-[#070191]"
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
                    ? 'text-[#1f0391] font-bold'
                    : 'text-gray-900'
                "
              >
                Profil privé
              </span>
              <span
                id="privacy-setting-0-description"
                class="block text-sm"
                :class="!isProfileVisible ? 'text-gray-700' : 'text-gray-500'"
              >
                Votre profil ne sera pas visible des organisations.
              </span>
            </div>
          </label>

          <label
            class="relative rounded-bl-md rounded-br-md border p-4 flex cursor-pointer"
            :class="
              isProfileVisible
                ? 'bg-blue-50 border-[#070191] z-10'
                : 'border-gray-200'
            "
          >
            <input
              type="radio"
              name="is_visible"
              :value="true"
              class="form-radio h-4 w-4 mt-0.5 cursor-pointer text-[#070191] border-[#d2d6dc] focus:ring-[#070191]"
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
                    ? 'text-[#1f0391] font-bold'
                    : 'text-gray-900'
                "
              >
                Profil public
              </span>
              <span
                id="privacy-setting-1-description"
                class="block text-sm"
                :class="isProfileVisible ? 'text-gray-700' : 'text-gray-500'"
              >
                Votre profil sera visible des organisations.
              </span>
            </div>
          </label>
        </div>
      </fieldset>

      <div class="mt-8">
        <el-button type="primary" :loading="loading" class="" @click="onSubmit">
          Enregistrer les modifications
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  layout: 'profile',
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
      rules: {
        domaines: {
          required: true,
          message: "Sélectionnez au moins un domaine d'action",
          trigger: 'blur',
        },
      },
    }
  },
  computed: {
    isProfileVisible() {
      return this.form.is_visible
    },
    domainesSelected: {
      get() {
        return this.form.domaines.map((item) => item.name.fr)
      },
      set(items) {
        //
      },
    },
  },
  methods: {
    isDomaineSelected(id) {
      return this.form.domaines.filter((item) => item.id == id).length > 0
    },
    handleClickDomaine(domaine) {
      if (this.isDomaineSelected(domaine.id)) {
        this.form.domaines = this.form.domaines.filter(
          (item) => item.id !== domaine.id
        )
      } else {
        this.$set(this.form, 'domaines', [...this.form.domaines, domaine])
      }
    },
    onSubmit() {
      this.loading = true
      this.$refs.profileForm.validate((valid, fields) => {
        if (valid) {
          this.$store
            .dispatch('user/updateProfile', {
              id: this.$store.getters.profile.id,
              ...this.form,
            })
            .then(() => {
              this.loading = false
              this.$message({
                message: 'Vos préférences ont été mises à jour.',
                type: 'success',
              })
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.showErrors(fields)
          this.loading = false
        }
      })
    },
  },
}
</script>

<style lang="postcss" scoped>
::v-deep .el-form-item {
  @apply mb-3;
}
</style>
