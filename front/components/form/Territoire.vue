<template>
  <el-form
    ref="territoireForm"
    :model="form"
    label-position="top"
    :rules="rules"
  >
    <div>
      <div class="mb-6 text-1-5xl font-bold text-gray-800">
        Informations générales
      </div>

      <el-form-item label="Publication de la page" prop="is_published">
        <el-checkbox v-model="form.is_published">
          Cochez la case pour publier la page
        </el-checkbox>
      </el-form-item>

      <el-form-item label="Nom du territoire" prop="name">
        <el-input v-model="form.name" placeholder="Titre" />
      </el-form-item>

      <el-form-item label="Suffix du titre" prop="suffix_title">
        <el-input
          v-model="form.suffix_title"
          placeholder="à Marseille / au Pays de grasse / dans les Alpes-Maritimes"
        >
          <template slot="prepend">Dévenez bénévole</template>
        </el-input>
      </el-form-item>

      <el-form-item label="Type" prop="type">
        <el-select v-model="form.type" placeholder="Sélectionner le type">
          <el-option
            v-for="item in $store.getters.taxonomies.territoires_types.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </el-form-item>

      <el-form-item label="Département" prop="department">
        <el-select
          v-model="form.department"
          filterable
          placeholder="Département"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.departments.terms"
            :key="item.value"
            :label="`${item.value} - ${item.label}`"
            :value="item.value"
          />
        </el-select>
      </el-form-item>

      <el-form-item
        v-if="form.type != 'department'"
        label="Liste des codes postaux"
        prop="zips"
        class="flex-1"
      >
        <el-select
          v-model="form.zips"
          multiple
          allow-create
          filterable
          default-first-option
          placeholder="Saisissez tous les codes postaux"
        >
        </el-select>
      </el-form-item>
    </div>

    <template v-if="$store.getters.contextRole == 'admin'">
      <div>
        <div class="mb-6 text-1-5xl font-bold text-gray-800">SEO</div>

        <el-form-item
          label="Titre pour le recrutement"
          prop="seo_recruit_title"
        >
          <el-input
            v-model="form.seo_recruit_title"
            placeholder="Ces associations recrutent des bénévoles"
          />
        </el-form-item>
        <el-form-item
          label="Description pour le recrutement"
          prop="seo_recruit_description"
        >
          <client-only>
            <RichEditor v-model="form.seo_recruit_description" />
          </client-only>
        </el-form-item>

        <el-form-item label="Titre pour l'engagement" prop="seo_engage_title">
          <el-input
            v-model="form.seo_engage_title"
            placeholder="Engagez-vous pour une cause bénévole qui vous ressemble"
          />
        </el-form-item>

        <el-form-item
          label="Description pour le recrutement"
          prop="seo_engage_paragraphs"
        >
          <Paragraph
            :schema="[
              { key: 'title', label: 'Titre', type: 'text' },
              { key: 'description', label: 'Description', type: 'richtext' },
            ]"
            :rules="{
              title: [
                {
                  required: true,
                  message: 'Titre obligatoire',
                  trigger: 'blur',
                },
              ],
              description: [
                {
                  required: true,
                  message: 'Description obligatoire',
                  trigger: 'blur',
                },
              ],
            }"
            :items="form.seo_engage_paragraphs"
            @add="onParagraphAddItem('seo_engage_paragraphs', $event)"
            @update="onParagraphUpdateItem('seo_engage_paragraphs', $event)"
            @remove="onParagraphRemoveItem('seo_engage_paragraphs', $event)"
          />
        </el-form-item>
      </div>

      <div>
        <div class="mb-6 text-1-5xl font-bold text-gray-800">Associations</div>
        <el-form-item>
          <Paragraph
            :schema="[
              { key: 'structure', type: 'autocomplete', model: 'structure' },
            ]"
            :items="form.structures"
            @add="onParagraphAddItem('structures', $event)"
            @update="onParagraphUpdateItem('structures', $event)"
            @remove="onParagraphRemoveItem('structures', $event)"
          />
        </el-form-item>
      </div>

      <div>
        <div class="mb-6 text-1-5xl font-bold text-gray-800">
          Articles du blog
        </div>

        <el-form-item
          label="Tags pour récupérer les articles du blog"
          prop="tags"
          class="flex-1"
        >
          <el-select
            v-model="form.tags"
            multiple
            allow-create
            filterable
            default-first-option
            placeholder="Saisissez tous les tags"
          >
          </el-select>
        </el-form-item>
      </div>
    </template>

    <div>
      <div class="mb-6 text-1-5xl font-bold text-gray-800">Images</div>

      <ImageField
        model="territoire"
        :model-id="form.id ? form.id : null"
        :min-width="1200"
        :min-height="450"
        :field="form.banner"
        field-name="banner"
        :aspect-ratio="8 / 3"
        label="Bannière"
        @add-or-crop="handleAddOrCrop($event)"
        @delete="handleDelete($event)"
      ></ImageField>

      <ImageField
        v-if="form.type == 'city'"
        model="territoire"
        :model-id="form.id ? form.id : null"
        :max-size="1000000"
        :field="form.logo"
        field-name="logo"
        :aspect-ratio="0"
        label="Logo"
        @add-or-crop="handleAddOrCrop($event)"
        @delete="handleDelete($event)"
      ></ImageField>
    </div>

    <div class="flex pt-2">
      <el-button type="primary" :loading="loading" @click="onSubmit">
        Enregistrer
      </el-button>
    </div>
  </el-form>
</template>

<script>
import FormMixin from '@/mixins/Form'
import ParagraphMixin from '@/mixins/ParagraphMixin'

export default {
  mixins: [FormMixin, ParagraphMixin],
  props: {
    territoire: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      loading: false,
      form: {
        ...this.territoire,
        seo_recruit_title:
          this.territoire.seo_recruit_title ??
          'Ces associations recrutent des bénévoles',
        seo_engage_title:
          this.territoire.seo_engage_title ??
          'Engagez-vous pour une cause bénévole qui vous ressemble',
      },
      uploads: [],
      rules: {
        name: [
          {
            required: true,
            message: 'Veuillez renseigner un nom',
            trigger: 'blur',
          },
        ],
        suffix_title: [
          {
            required: true,
            message: 'Veuillez renseigner un suffix de titre',
            trigger: 'blur',
          },
        ],
        type: [
          {
            required: true,
            message: 'Veuillez renseigner un type',
            trigger: 'blur',
          },
        ],
        department: [
          {
            required: true,
            message: 'Veuillez renseigner un département',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.territoireForm.validate(async (valid, fields) => {
        if (valid) {
          if (this.territoire.id) {
            await this.$api.updateTerritoire(this.form.id, this.form)
            if (this.$store.getters.contextRole == 'responsable_territoire') {
              await this.$store.dispatch('auth/fetchUser')
            }
          } else {
            const { data } = await this.$api.addTerritoire(this.form)
            this.form = data
          }
          if (this.form.id) {
            await this.uploadImages()
          }
          this.loading = false
          this.$router.back()
          this.$message({
            message: 'Le territoire a été enregistrée !',
            type: 'success',
          })
        } else {
          this.showErrors(fields)
          this.loading = false
        }
      })
    },
    async uploadImages() {
      const promises = []
      this.uploads.forEach((upload) => {
        promises.push(
          this.$api.uploadImage(
            this.form.id,
            'territoire',
            upload.blob,
            upload.cropSettings,
            upload.fieldName
          )
        )
      })
      await Promise.all(promises)
    },
    handleAddOrCrop($event) {
      const existingIndex = this.uploads.findIndex(
        (upload) => upload.fieldName === $event.fieldName
      )
      if (existingIndex != -1) {
        this.uploads.splice(existingIndex, 1, $event)
      } else {
        this.uploads.push($event)
      }
    },
    handleDelete($event) {
      this.uploads.splice(
        this.uploads.findIndex(
          (upload) => upload.fieldName === $event.fieldName
        ),
        1
      )
    },
  },
}
</script>
