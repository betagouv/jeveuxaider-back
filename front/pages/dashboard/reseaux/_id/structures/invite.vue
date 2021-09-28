<template>
  <div>
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ reseau.name }}
        </div>
        <div class="mb-12 font-bold text-[1.75rem] text-[#242526]">
          Inviter une antenne
        </div>
      </div>
      <div>
        <nuxt-link :to="`/dashboard/reseaux/${$route.params.id}/structures`">
          <el-button>Retour</el-button>
        </nuxt-link>
      </div>
    </div>
    <div class="px-12 max-w-2xl">
      <el-form
        ref="invitationForm"
        :model="form"
        label-position="top"
        :rules="rules"
      >
        <el-form-item
          label="Nom de l'antenne de votre réseau"
          prop="properties.antenne_name"
        >
          <ItemDescription container-class="mb-2">
            Par exemple : {{ reseau.name }} - Occitanie
          </ItemDescription>
          <el-input
            v-model="form.properties.antenne_name"
            placeholder="Renseignez le nom de l'antenne"
          />
        </el-form-item>
        <el-form-item label="Email" prop="email">
          <el-input v-model.trim="form.email" placeholder="Email" />
        </el-form-item>

        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Envoyer une invitation
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  layout: 'dashboard',
  async asyncData({ $api, store, error, params }) {
    if (!['tete_de_reseau', 'admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const reseau = await $api.getReseau(params.id)
    return {
      reseau,
    }
  },
  data() {
    return {
      loading: false,
      form: {
        user_id: this.$store.getters.user.id,
        role: 'responsable_antenne',
        invitable_id: this.$route.params.id,
        invitable_type: 'App\\Models\\Reseau',
        properties: {},
      },
      rules: {
        'properties.antenne_name': [
          {
            required: true,
            message: "Veuillez renseigner le nom de l'antenne",
            trigger: 'blur',
          },
        ],
        email: [
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
          {
            required: true,
            message: 'Veuillez renseigner une adresse email',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.invitationForm.validate((valid, fields) => {
        if (valid) {
          this.$api
            .addInvitation(this.form)
            .then(() => {
              this.loading = false
              this.$router.push(
                `/dashboard/reseaux/${this.$route.params.id}/structures`
              )
              this.$message.success({
                message: `Une notification email a été envoyée à ${this.form.email}.`,
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
