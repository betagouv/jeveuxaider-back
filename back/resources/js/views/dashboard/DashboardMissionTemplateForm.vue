<template>
  <div
    v-if="!$store.getters.loading"
    class="profile-form max-w-2xl pl-12 pb-12"
  >
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Page</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">{{ form.title }}</div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">
      Nouveau modèle
    </div>

    <el-form
      ref="missionTemplateForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <div class="mb-6 text-xl text-gray-800">Informations générales</div>

      <el-form-item label="Titre" prop="title">
        <el-input v-model="form.title" placeholder="Titre" />
      </el-form-item>

      <el-form-item label="Sous titre" prop="subtitle" class="flex-1">
        <el-input
          v-model="form.subtitle"
          name="subtitle"
          type="textarea"
          :autosize="{ minRows: 2, maxRows: 6 }"
          placeholder="Placeholder subtitle"
        ></el-input>
      </el-form-item>

      <el-form-item label="Domaine d'action" prop="domaine_id" class="flex-1">
        <el-select
          v-model="form.domaine_id"
          clearable
          placeholder="Sélectionner un domaine d'action"
        >
          <el-option
            v-for="item in tags"
            :key="item.id"
            :label="item.name.fr"
            :value="item.id"
          ></el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="Objectif" prop="objectif">
        <ckeditor
          v-model="form.objectif"
          :editor="editor"
          :config="editorConfig"
        ></ckeditor>
      </el-form-item>

      <el-form-item label="Description" prop="description">
        <ckeditor
          v-model="form.description"
          :editor="editor"
          :config="editorConfig"
        ></ckeditor>
      </el-form-item>

      <div class="mb-6 mt-12 flex text-xl text-gray-800">Visibilité</div>
      <item-description container-class="mb-3">
        Si vous souhaitez rendre ce modèle visible, cochez la case.
      </item-description>
      <el-form-item prop="published" class="flex-1">
        <el-checkbox v-model="form.published">En ligne</el-checkbox>
      </el-form-item>

      <div class="mb-6 mt-12 flex text-xl text-gray-800">
        Mission prioritaire
      </div>
      <item-description container-class="mb-3">
        Les modèles de missions prioritaires sont mises en avant lors de la
        création d'une nouvelle mission.
      </item-description>
      <el-form-item prop="priority" class="flex-1">
        <el-checkbox v-model="form.priority">Mission prioritaire</el-checkbox>
      </el-form-item>

      <ImageField
        :crop="false"
        accepted-files="image/svg+xml"
        :model="model"
        :model-id="form.id ? form.id : null"
        :field="form.image"
        :max-size="1000000"
        :preview-width="80"
        preview-area-class="bg-primary rounded-md p-3"
        label="Icone"
        label-class="mb-6 text-xl text-gray-800"
        description="Format accepté: SVG"
        @add-or-crop="icone = $event"
        @delete="icone = null"
      ></ImageField>

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit"
          >Enregistrer</el-button
        >
      </div>
    </el-form>
  </div>
</template>

<script>
import {
  getMissionTemplate,
  addOrUpdateMissionTemplate,
  fetchTags,
  uploadImage,
} from '@/api/app'
import ItemDescription from '@/components/forms/ItemDescription'
import CKEditorLight from '@/mixins/CKEditorLight.vue'
import ImageField from '@/components/forms/ImageField.vue'

export default {
  name: 'DashboardMissionTemplateForm',
  components: { ItemDescription, ImageField },
  mixins: [CKEditorLight],
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
      tags: [],
      model: 'mission-template',
      form: {
        published: true,
        priority: false,
      },
      icone: null,
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
        subtitle: [
          {
            required: true,
            message: 'Veuillez renseigner un sous-titre',
            trigger: 'blur',
          },
        ],
        domaine_id: [
          {
            required: true,
            message: "Veuillez sélectionner le domaine d'action",
            trigger: 'blur',
          },
        ],
        description: [
          {
            required: true,
            message: 'Veuillez renseigner une description',
            trigger: 'blur',
          },
        ],
        objectif: [
          {
            required: true,
            message: 'Veuillez renseigner un objectif',
            trigger: 'blur',
          },
        ],
      }
      return rules
    },
  },
  created() {
    fetchTags({ 'filter[type]': 'domaine' }).then((response) => {
      this.tags = response.data.data
    })

    if (this.mode == 'edit') {
      this.$store.commit('setLoading', true)
      getMissionTemplate(this.id)
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
      this.$refs['missionTemplateForm'].validate((valid) => {
        if (valid) {
          addOrUpdateMissionTemplate(this.id, this.form)
            .then((response) => {
              this.form = response.data
              if (this.icone) {
                uploadImage(
                  this.form.id,
                  this.model,
                  this.icone.blob,
                  null
                ).then(() => {
                  this.onSubmitEnd()
                })
              } else {
                this.onSubmitEnd()
              }
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.loading = false
        }
      })
    },
    onSubmitEnd() {
      this.loading = false
      this.$router.push('/dashboard/contents/mission-templates')
      this.$message({
        message: 'Le modèle a été enregistré !',
        type: 'success',
      })
    },
  },
}
</script>
