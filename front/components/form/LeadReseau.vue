<template>
  <div class="max-w-xl mx-auto bg-gray-100 p-6 sm:p-12 rounded-2xl">
    <el-form
      v-if="sent === false"
      ref="reseauForm"
      :model="form"
      label-position="top"
      :hide-required-asterisk="true"
      :rules="rules"
      class="form-register-steps"
    >
      <div class="flex flex-wrap -mx-2">
        <el-form-item
          label="Nom de votre réseau d'organisation"
          prop="name"
          class="w-full px-2"
        >
          <el-input
            v-model="form.name"
            label="Nom de votre réseau d'organisation"
            placeholder="Nom de votre réseau d'organisation"
          />
        </el-form-item>
        <el-form-item
          label="Prénom"
          prop="first_name"
          class="w-full sm:w-1/2 px-2"
        >
          <el-input
            v-model="form.first_name"
            label="Prénom"
            autocomplete="new-password"
            placeholder="Prénom"
          />
        </el-form-item>
        <el-form-item label="Nom" prop="last_name" class="w-full sm:w-1/2 px-2">
          <el-input
            v-model="form.last_name"
            label="Nom"
            autocomplete="new-password"
            placeholder="Nom"
          />
        </el-form-item>
        <el-form-item label="E-mail" prop="email" class="w-full px-2">
          <el-input
            v-model.trim="form.email"
            label="E-mail"
            autocomplete="new-password"
            placeholder="E-mail"
          />
        </el-form-item>
      </div>
      <el-button
        type="primary"
        :loading="loading"
        class="!w-full !flex !justify-center !p-4 !border !border-transparent !rounded-lg !shadow-lg !text-lg !font-bold !text-white !bg-[#16a972] hover:!shadow-lg hover:!scale-105 !transform !transition !mt-4 !leading-none"
        @click="onSubmit"
      >
        Demander la gestion d'un réseau
      </el-button>
    </el-form>
    <div v-else class="text-center text-lg text-gray-500 font-semibold">
      <p class="text-5xl">👍</p>
      <p>
        Votre demande de gestion d’un réseau associatif a bien été envoyée à
        l’administration de JeVeuxAider.gouv.fr
      </p>
      <p>
        Vous serez prochainement contacté par notre équipe pour la suite de
        l’aventure.
      </p>
    </div>
  </div>
</template>
<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  data() {
    return {
      loading: false,
      sent: false,
      form: {},
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
        ],
        name: [
          {
            required: true,
            message: 'Nom du réseau obligatoire',
            trigger: 'blur',
          },
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
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.reseauForm.validate(async (valid, fields) => {
        if (valid) {
          console.log('go !', this.form)
          const res = await this.$api.reseauLead(this.form)
          if (res.data) {
            this.sent = true
          } else {
            this.loading = false
          }
        } else {
          this.showErrors(fields)
          this.loading = false
        }
      })
    },
  },
}
</script>
