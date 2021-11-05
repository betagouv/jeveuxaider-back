<template>
  <el-form
    ref="missionTemplateForm"
    :model="form"
    label-position="top"
    :rules="rules"
  >
    <div class="mb-6 text-1-5xl font-boldtext-[#242526]">
      Informations générales
    </div>

    <el-form-item label="Titre" prop="title">
      <ItemDescription container-class="mb-3">
        Il s'agit du titre principal affiché sur la page de la mission.
      </ItemDescription>
      <el-input
        v-model="form.title"
        placeholder="Titre"
        type="textarea"
        :autosize="{ minRows: 2, maxRows: 6 }"
      />
    </el-form-item>

    <el-form-item label="Titre court" prop="subtitle" class="flex-1">
      <ItemDescription container-class="mb-3">
        Le titre court sera utilisé comme libellé pour le filtre "Type de
        mission" dans la recherche.
      </ItemDescription>
      <el-input
        v-model="form.subtitle"
        name="subtitle"
        placeholder="Titre court"
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
      <RichEditor v-model="form.objectif" />
    </el-form-item>

    <el-form-item label="Description" prop="description">
      <RichEditor v-model="form.description" />
    </el-form-item>

    <div v-if="$store.getters.contextRole == 'admin'">
      <div class="mb-6 mt-12 flex text-xl text-[#242526]">Visibilité</div>
      <item-description container-class="mb-3">
        Si vous souhaitez rendre ce modèle visible, cochez la case.
      </item-description>
      <el-form-item prop="published" class="flex-1">
        <el-checkbox v-model="form.published">En ligne</el-checkbox>
      </el-form-item>

      <div class="mb-6 mt-12 flex text-xl text-[#242526]">
        Mission prioritaire
      </div>
      <item-description container-class="mb-3">
        Les modèles de missions prioritaires sont mises en avant lors de la
        création d'une nouvelle mission.
      </item-description>
      <el-form-item prop="priority" class="flex-1">
        <el-checkbox v-model="form.priority">Mission prioritaire</el-checkbox>
      </el-form-item>
    </div>

    <ImageField
      :model="model"
      :model-id="form.id ? form.id : null"
      :max-size="2000000"
      :preview-width="'200px'"
      :field="form.photo"
      :aspect-ratio="300 / 140"
      label="Photo"
      field-name="photo"
      :min-width="120"
      component-classes="mb-8"
      @add-or-crop="photo = $event"
      @delete="photo = null"
    />
    <!--
    <ImageField
      :crop="false"
      accepted-files="image/svg+xml"
      :model="model"
      :model-id="form.id ? form.id : null"
      :field="form.image"
      :max-size="1000000"
      :preview-width="'80px'"
      field-name="icon"
      preview-area-class="bg-primary rounded-md p-3"
      label="Icone"
      label-class="mb-6 text-1-5xl font-bold text-[#242526]"
      description="Format accepté: SVG"
      @add-or-crop="icone = $event"
      @delete="icone = null"
    ></ImageField> -->

    <div class="flex pt-2">
      <el-button type="primary" :loading="loading" @click="onSubmit"
        >Enregistrer</el-button
      >
    </div>
  </el-form>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  props: {
    template: {
      type: Object,
      default() {
        return {
          published: true,
          priority: false,
        }
      },
    },
    onSubmitEnd: {
      type: Function,
      default() {
        this.$router.push('/dashboard/contents/templates')
        this.$message({
          message: 'Le modèle a été enregistré !',
          type: 'success',
        })
      },
    },
  },
  data() {
    return {
      loading: false,
      tags: [],
      model: 'mission-template',
      form: { ...this.template },
      icone: null,
      photo: null,
    }
  },
  computed: {
    rules() {
      const rules = {
        title: [
          {
            required: true,
            message: 'Veuillez renseigner un titre',
            trigger: 'blur',
          },
          {
            max: 255,
            message: 'Taille maximale : 255 caractères',
            trigger: 'blur',
          },
        ],
        subtitle: [
          {
            required: true,
            message: 'Veuillez renseigner un sous-titre',
            trigger: 'blur',
          },
          {
            max: 255,
            message: 'Taille maximale : 255 caractères',
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
    this.$api.fetchTags({ 'filter[type]': 'domaine' }).then((response) => {
      this.tags = response.data.data
    })
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.missionTemplateForm.validate((valid, fields) => {
        if (valid) {
          this.$api
            .addOrUpdateMissionTemplate(this.template.id, this.form)
            .then(async (response) => {
              this.form = response.data
              if (this.icone) {
                await this.$api.uploadImage(
                  this.form.id,
                  this.model,
                  this.icone.blob,
                  '',
                  this.icone.fieldName
                )
              }
              if (this.photo) {
                await this.$api.uploadImage(
                  this.form.id,
                  this.model,
                  this.photo.blob,
                  this.photo.cropSettings,
                  this.photo.fieldName
                )
              }
              this.loading = false
              this.onSubmitEnd()
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
