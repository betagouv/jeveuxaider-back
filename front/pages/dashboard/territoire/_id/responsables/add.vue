<template>
  <div class="max-w-2xl">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ territoire.name }}
        </div>
        <div class="mb-12 font-bold text-2-5xl text-gray-800">
          Inviter un nouveau responsable
        </div>
      </div>
    </div>
    <div class="px-12">
      <el-form
        ref="invitationForm"
        :model="form"
        label-position="top"
        :rules="rules"
      >
        <el-form-item label="Email" prop="email">
          <el-input v-model.trim="form.email" placeholder="Email" />
        </el-form-item>

        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Envoyer l'invitation
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
  async asyncData({ $api, params }) {
    const territoire = await $api.getTerritoire(params.id)
    return {
      territoire,
    }
  },
  data() {
    return {
      form: {
        user_id: this.$store.getters.user.id,
        role: 'responsable_territoire',
        invitable_id: this.$route.params.id,
        invitable_type: 'App\\Models\\Territoire',
      },
      rules: {
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
      loading: false,
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
                `/dashboard/territoire/${this.territoire.id}/responsables`
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

<style lang="sass" scoped>
.el-radio__label
  @apply text-gray-800 font-medium
  .description
    @apply text-secondary font-light mt-1
</style>
