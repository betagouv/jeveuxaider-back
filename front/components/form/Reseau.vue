<template>
  <el-form
    ref="reseauForm"
    class=""
    :model="form"
    label-position="top"
    :rules="rules"
  >
    <div class="flex justify-between mb-6 text-1-5xl font-bold">
      <div class="text-[#242526]">Informations</div>
    </div>

    <el-form-item label="Nom du réseau" prop="name">
      <el-input v-model="form.name" placeholder="Ex: Croix-Rouge" />
    </el-form-item>

    <div class="flex pt-2 items-center">
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
    reseau: {
      type: Object,
      default() {},
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.reseau },
      rules: {
        name: [
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
      this.$refs.reseauForm.validate((valid, fields) => {
        if (valid) {
          this.$api
            .addOrUpdateReseau(this.form)
            .then(() => {
              this.loading = false
              this.$router.push('/dashboard/reseaux')
              this.$message({
                message: 'Le réseau a été enregistré !',
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
