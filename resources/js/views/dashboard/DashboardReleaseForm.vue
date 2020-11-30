<template>
  <div
    v-if="!$store.getters.loading"
    class="profile-form max-w-2xl pl-12 pb-12"
  >
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Release</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">
          {{ form.title }}
        </div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">
      Nouvelle release
    </div>

    <el-form
      ref="releaseForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <div class="mb-6 text-xl text-gray-800">Informations générales</div>

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
        <ckeditor
          v-model="form.description"
          :editor="editor"
          :config="editorConfig"
        />
      </el-form-item>

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Enregistrer
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { getRelease, updateRelease, addRelease } from '@/api/app'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

export default {
  name: 'DashboardReleaseForm',
  props: {
    mode: {
      type: String,
      required: true,
    },
    id: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      loading: false,
      form: {},
      editor: ClassicEditor,
      editorConfig: {
        toolbar: [
          'bold',
          'italic',
          '|',
          'link',
          'bulletedList',
          'numberedList',
        ],
      },
    }
  },
  computed: {
    rules() {
      let rules = {
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
      }
      return rules
    },
  },
  created() {
    if (this.mode == 'edit') {
      this.$store.commit('setLoading', true)
      getRelease(this.id)
        .then((response) => {
          this.$store.commit('setLoading', false)
          this.form = response.data
        })
        .catch(() => {
          this.loading = false
        })
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['releaseForm'].validate((valid) => {
        if (valid) {
          if (this.id) {
            updateRelease(this.form.id, this.form)
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
            addRelease(this.form)
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
          this.loading = false
        }
      })
    },
  },
}
</script>
