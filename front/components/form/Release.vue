<template>
  <el-form ref="releaseForm" :model="form" label-position="top" :rules="rules">
    <div class="mb-6 text-1-5xl font-boldtext-[#242526]">
      Informations générales
    </div>

    <el-form-item label="Titre" prop="title">
      <el-input v-model="form.title" placeholder="Titre" />
    </el-form-item>

    <el-form-item label="Date" prop="date">
      <el-date-picker
        v-model="form.date"
        class="w-full"
        type="datetime"
        placeholder="Date de début"
        value-format="yyyy-MM-dd HH:mm:ss"
        default-time="09:00:00"
      />
    </el-form-item>

    <el-form-item label="Description" prop="description">
      <RichEditor v-model="form.description" />
    </el-form-item>

    <div class="flex pt-2">
      <el-button type="primary" :loading="loading" @click="onSubmit">
        Enregistrer
      </el-button>
    </div>
  </el-form>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],

  props: {
    release: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.release },
      rules: {
        title: [
          {
            required: true,
            message: 'Veuillez renseigner un titre',
            trigger: 'blur',
          },
        ],
        description: [
          {
            required: true,
            message: 'Veuillez renseigner un nom',
            trigger: 'blur',
          },
        ],
        date: [
          {
            required: true,
            message: 'Veuillez renseigner une date',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.releaseForm.validate((valid, fields) => {
        if (valid) {
          if (this.release.id) {
            this.$api
              .updateRelease(this.form.id, this.form)
              .then(() => {
                this.loading = false
                this.$router.push('/dashboard/contents/releases')
                this.$message({
                  message: 'La release a été enregistrée !',
                  type: 'success',
                })
              })
              .catch(() => {
                this.loading = false
              })
          } else {
            this.$api
              .addRelease(this.form)
              .then(() => {
                this.loading = false
                this.$router.push('/dashboard/contents/releases')
                this.$message({
                  message: 'La release a été enregistrée !',
                  type: 'success',
                })
              })
              .catch(() => {
                this.loading = false
              })
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
