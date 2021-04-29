<template>
  <div class="">
    <div class="font-bold text-2-5xl text-gray-800 mb-4">Compétences</div>

    <div class="mb-8 text-md text-gray-600">
      Enrichissez votre profil avec les compétences que vous souhaitez mettre au
      service des organisations publiques ou associatives.
    </div>

    <div class="mb-8">
      <div class="form-register-steps el-form--label-top">
        <label for="compentences" class="el-form-item__label"
          >Renseignez vos compétences</label
        >
        <ProfileAlgoliaSkillsInput
          :items="form.skills"
          @add-item="handleSelectItems"
        />
      </div>
    </div>

    <div v-if="form.skills.length" class="mb-10">
      <div class="flex flex-wrap -m-1">
        <div
          v-for="item in form.skills"
          :key="item.id"
          class="flex items-center space-x-4 px-4 py-3 rounded-lg border border-blue-800 bg-white m-1"
        >
          <div class="flex-none text-sm text-blue-800 font-bold">
            {{ item.name.fr }}
          </div>
          <div
            class="flex-none cursor-pointer w-4 h-4 text-blue-800 hover:text-blue-900"
            @click="handleRemoveSkill(item.id)"
            v-html="
              require('@/assets/images/icones/heroicon/close.svg?include')
            "
          />
        </div>
      </div>
    </div>

    <div class="mt-8">
      <el-button type="primary" :loading="loading" class="" @click="onSubmit">
        Enregistrer les modifications
      </el-button>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'profile',
  asyncData({ $api, store }) {
    return {
      form: { ...store.getters.profile },
    }
  },
  data() {
    return {
      loading: false,
    }
  },
  methods: {
    handleSelectItems(item) {
      this.$set(this.form, 'skills', [...this.form.skills, item])
    },
    handleRemoveSkill(id) {
      this.form.skills = this.form.skills.filter((item) => item.id !== id)
    },
    async onSubmit() {
      this.loading = true
      await this.$store.dispatch('user/updateProfile', {
        id: this.$store.getters.profile.id,
        ...this.form,
      })
      this.loading = false
      this.$message({
        message: 'Vos compétences ont été mises à jour.',
        type: 'success',
      })
    },
  },
}
</script>
