<template>
  <div
    v-if="!$store.getters.loading"
    class="profile-form max-w-2xl pl-12 pb-12"
  >
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">FAQ</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">
          {{ form.title }}
        </div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">
      Nouvelle question
    </div>

    <el-form ref="faqForm" :model="form" label-position="top" :rules="rules">
      <div class="mb-6 text-xl text-gray-800">Informations générales</div>

      <el-form-item label="Question" prop="title">
        <el-input v-model="form.title" placeholder="Question" />
      </el-form-item>

      <el-form-item label="Poids de la question" prop="weight">
        <item-description container-class="mb-3">
          Les questions s'afficheront par ordre décroissant.
        </item-description>
        <el-input-number
          v-model="form.weight"
          :step="1"
          :min="1"
          class="w-full"
        />
      </el-form-item>

      <!-- <el-form-item label="Description" prop="description" class="flex-1">
        <el-input
          v-model="form.description"
          name="description"
          type="textarea"
          :autosize="{ minRows: 6, maxRows: 20 }"
          placeholder="Rédigez la réponse"
        ></el-input>
      </el-form-item>-->

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
import { getFaq, updateFaq, addFaq } from '@/api/app'
import ItemDescription from '@/components/forms/ItemDescription'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

export default {
  name: 'FaqForm',
  components: { ItemDescription },
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
      form: {
        weight: 1,
      },
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
        weight: [
          {
            required: true,
            message: 'Veuillez renseigner un poids',
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
      getFaq(this.id)
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
      this.$refs['faqForm'].validate((valid) => {
        if (valid) {
          if (this.id) {
            updateFaq(this.form.id, this.form)
              .then(() => {
                this.loading = false
                this.$router.push('/dashboard/contents/faqs')
                this.$message({
                  message: 'La question a été enregistrée !',
                  type: 'success',
                })
              })
              .catch(() => {
                this.loading = false
              })
          } else {
            addFaq(this.form)
              .then(() => {
                this.loading = false
                this.$router.push('/dashboard/contents/faqs')
                this.$message({
                  message: 'La question a été enregistrée !',
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

<style lang="sass" scoped>
::v-deep
  .el-input-number__decrease,
  .el-input-number__increase
    bottom: 1px
    display: flex
    align-items: center
    justify-content: center
</style>
