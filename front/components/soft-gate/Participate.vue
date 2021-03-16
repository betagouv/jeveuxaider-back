<template>
  <div>
    <div class="text-center mb-6">
      <div
        class="text-gray-900 font-extrabold text-2xl lg:text-3xl leading-8 mb-2 lg:mb-3"
      >
        Proposez votre aide
      </div>
      <div
        class="text-gray-500 font-semibold text-lg lg:text-xl max-w-md mx-auto"
      >
        Vous allez être mis en relation avec
        <span class="font-extrabold">{{
          $store.getters.missionSelected.responsable.first_name
        }}</span
        >, responsable de la mission chez
        <span class="font-extrabold">{{
          $store.getters.missionSelected.structure.name
        }}</span
        >.
      </div>
    </div>
    <div class="mx-auto max-w-sm">
      <el-form
        ref="participateForm"
        :model="form"
        :rules="rules"
        :hide-required-asterisk="true"
        class="mt-4 mb-0 form-center"
      >
        <el-form-item class="mb-2" prop="content">
          <textarea
            v-model="form.content"
            placeholder=""
            class="input-shadow w-full bg-white rounded-lg border-0 p-6 leading-6 text-gray-900"
            rows="7"
            autocomplete="off"
          ></textarea>
        </el-form-item>
        <el-button
          :loading="loading"
          class="font-bold max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-xl leading-6 rounded-full text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
          @click.prevent="onSubmit"
        >
          Envoyer
        </el-button>
      </el-form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SoftGateParticipate',
  data() {
    return {
      loading: false,
      form: {
        content: `Bonjour ${this.$store.getters.missionSelected.responsable.first_name},\nJe souhaite participer à cette mission et apporter mon aide. \nJe me tiens disponible pour échanger et débuter la mission 🙂\n${this.$store.getters.user.profile.first_name}`,
      },
      rules: {
        content: [
          {
            required: true,
            message: 'Entrez un message.',
            trigger: 'blur',
          },
          {
            min: 10,
            message: 'Votre message est trop court.',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    onSubmit() {
      this.$refs.participateForm.validate((valid) => {
        if (valid) {
          this.loading = true
          this.$api
            .addParticipation(
              this.$store.getters.missionSelected.id,
              this.$store.getters.profile.id,
              this.form.content
            )
            .then(() => {
              window.apieng && window.apieng('trackApplication')
              this.$message({
                message:
                  'Votre participation a été enregistrée et est en attente de validation !',
                type: 'success',
              })
              this.$store.dispatch('auth/fetchUser').then(() => {
                this.loading = false
                this.$emit('next')
              })
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
