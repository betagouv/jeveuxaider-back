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
          <ParagraphItems
            :fields="[
              { key: 'title', label: 'Titre', type: 'text', required: true },
              {
                key: 'description',
                label: 'Description',
                type: 'richtext',
                required: true,
              },
            ]"
            :items="form.seo_engage_paragraphs"
            @update-items="onUpdateItems"
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
      form: { ...this.territoire },
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
      },
    }
  },
  methods: {
    onUpdateItems(items) {
      this.form.seo_engage_paragraphs = items
    },
    onSubmit() {
      this.loading = true
      this.$refs.territoireForm.validate((valid, fields) => {
        if (valid) {
          if (this.territoire.id) {
            this.$api
              .updateTerritoire(this.form.id, this.form)
              .then(async () => {
                this.loading = false
                this.$router.back()
                // Si responsable on refetch profile
                console.log('role', this.$store.getters.contextRole)
                if (
                  this.$store.getters.contextRole == 'responsable_territoire'
                ) {
                  console.log('fetchUser')

                  await this.$store.dispatch('auth/fetchUser')
                }
                this.$message({
                  message: 'Le territoire a été enregistrée !',
                  type: 'success',
                })
              })
              .catch(() => {
                this.loading = false
              })
          } else {
            this.$api
              .addTerritoire(this.form)
              .then(() => {
                this.loading = false
                this.$router.back()
                this.$message({
                  message: 'Le territoire a été enregistrée !',
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
