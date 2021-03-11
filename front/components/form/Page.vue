<template>
  <el-form ref="pageForm" :model="form" label-position="top" :rules="rules">
    <div class="mb-6 text-xl text-gray-800">Informations générales</div>

    <el-form-item label="Titre" prop="title">
      <el-input v-model="form.title" placeholder="Titre" />
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
export default {
  props: {
    page: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.page },
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
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.pageForm.validate((valid) => {
        if (valid) {
          if (this.id) {
            this.$api
              .updatePage(this.form.id, this.form)
              .then(() => {
                this.loading = false
                this.$router.push('/dashboard/contents/pages')
                this.$message({
                  message: 'La page a été enregistrée !',
                  type: 'success',
                })
              })
              .catch(() => {
                this.loading = false
              })
          } else {
            this.$api
              .addPage(this.form)
              .then(() => {
                this.loading = false
                this.$router.push('/dashboard/contents/pages')
                this.$message({
                  message: 'La page a été enregistrée !',
                  type: 'success',
                })
              })
              .catch(() => {
                this.loading = false
              })
          }
        } else {
          this.loading = false
        }
      })
    },
  },
}
</script>
