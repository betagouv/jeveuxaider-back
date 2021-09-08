<template>
  <div>
    <div class="text-center">
      <div
        class="text-2xl sm:text-[38px] sm:leading-tight font-bold text-primary tracking-[-1px]"
      >
        <template v-if="form.grade > 3">
          <h1>
            <span class="font-extrabold">{{ benevole.first_name }}</span
            >, comment s'est déroulée votre mission&nbsp;?
          </h1>

          <div class="font-medium text-md sm:text-xl text-[#808080]">
            {{ mission.name }}
          </div>
        </template>

        <div v-else class="max-w-[678px]">
          Nous sommes navrés d’apprendre que la mission s’est mal déroulée.
        </div>
      </div>

      <transition name="fade" appear>
        <el-form
          ref="testimonyForm"
          :model="form"
          :rules="rules"
          class="py-8 max-w-[638px] mx-auto"
          style="filter: drop-shadow(0px 20px 50px rgba(0, 0, 0, 0.15))"
        >
          <el-form-item prop="testimony" class="">
            <div class="bg-white p-8 rounded-t-xl overflow-hidden">
              <client-only>
                <textarea-autosize
                  v-model="form.testimony"
                  :placeholder="placeholder"
                  rows="5"
                  class="m-auto w-full outline-none leading-tight custom-scrollbar text-base"
                />
              </client-only>
            </div>

            <button
              class="bg-primary p-4 uppercase font-extrabold text-sm tracking-wide rounded-b-xl overflow-hidden text-white w-full"
              @click.prevent="onClick"
            >
              {{ labelCta }}
            </button>
          </el-form-item>
        </el-form>
      </transition>
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  props: {
    mission: {
      type: Object,
      required: true,
    },
    benevole: {
      type: Object,
      required: true,
    },
    initialForm: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      form: { ...this.initialForm },
      rules: {
        testimony: [
          {
            required: true,
            message: 'Votre témoignage est requis',
            trigger: 'blur',
          },
          {
            min: 50,
            message: `La taille minimum est d'au moins 50 caractères`,
            trigger: 'blur',
          },
        ],
      },
    }
  },
  computed: {
    placeholder() {
      return this.form?.grade < 3
        ? 'Dites-nous comment améliorer la qualité de cette mission'
        : 'Partagez votre expérience avec la communauté de bénévoles'
    },
    labelCta() {
      return this.form?.grade < 3
        ? 'Partager mon retour'
        : 'Publier mon témoignage'
    },
  },
  beforeDestroy() {
    this.$emit('destroy', this.form)
  },
  methods: {
    onClick() {
      this.$refs.testimonyForm.validate((valid, fields) => {
        if (valid) {
          this.$emit('submit', this.form)
        } else {
          this.showErrors(fields)
        }
      })
    },
  },
}
</script>
