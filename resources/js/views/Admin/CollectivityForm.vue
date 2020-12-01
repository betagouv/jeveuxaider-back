<template>
  <div
    v-if="!$store.getters.loading"
    class="profile-form max-w-2xl pl-12 pb-12"
  >
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Collectivité</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">
          {{ form.name }}
        </div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">
      Nouvelle collectivité
    </div>

    <el-form
      ref="collectivityForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <div class="mb-6 text-xl text-gray-800">Informations générales</div>

      <el-form-item label="Nom de la collectivité" prop="name">
        <el-input
          v-model="form.name"
          :disabled="!canEditField"
          placeholder="Nom de la collectivité"
        />
        <item-description>
          Accessible à l'adresse : {{ baseUrl }}/territoires/{{
            form.name | slugify
          }}
        </item-description>
      </el-form-item>

      <el-form-item label="Titre de la page" prop="title">
        <el-input
          v-model="form.title"
          placeholder="Rejoignez la Réserve Civique dans votre collectivité"
        />
      </el-form-item>

      <el-form-item label="Description" prop="description" class="flex-1">
        <el-input
          v-model="form.description"
          type="textarea"
          maxlength="210"
          show-word-limit
          :autosize="{ minRows: 5 }"
          placeholder="Trouvez une mission d'intérêt général qui vous ressemble et faites vivre l’engagement local."
        />
      </el-form-item>

      <el-form-item
        v-if="$store.getters.contextRole == 'admin'"
        label="Type"
        prop="type"
      >
        <el-select v-model="form.type" placeholder="Sélectionner le type">
          <el-option
            v-for="item in $store.getters.taxonomies.collectivities_types.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </el-form-item>

      <el-form-item
        v-if="form.type == 'department'"
        label="Département"
        prop="department"
      >
        <el-select
          v-model="form.department"
          filterable
          placeholder="Sélectionnez le département"
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
        v-if="form.type == 'commune'"
        label="Liste des codes postaux"
        prop="zips"
        class="flex-1"
      >
        <el-select
          v-model="form.zips"
          :disabled="!canEditField"
          multiple
          allow-create
          filterable
          default-first-option
          placeholder="Saisissez tous les codes postaux"
        >
        </el-select>
      </el-form-item>

      <ImageField
        :model="model"
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

      <template v-if="form.type == 'commune'">
        <ImageField
          :model="model"
          :model-id="form.id ? form.id : null"
          :max-size="1000000"
          :field="form.logo"
          field-name="logo"
          :aspect-ratio="0"
          label="Logo"
          @add-or-crop="handleAddOrCrop($event)"
          @delete="handleDelete($event)"
        ></ImageField>

        <ImageField
          v-for="index in 6"
          :key="index"
          :model="model"
          :model-id="form.id ? form.id : null"
          :max-size="2000000"
          :min-width="300"
          :min-height="300"
          :aspect-ratio="1"
          :field="form[`image_${index}`]"
          :field-name="`image_${index}`"
          :label="`Illustration #${index}`"
          @add-or-crop="handleAddOrCrop($event)"
          @delete="handleDelete($event)"
        ></ImageField>
      </template>

      <template v-if="$store.getters.contextRole == 'admin'">
        <div class="mb-6 flex text-xl text-gray-800">Visibilité</div>
        <item-description container-class="mb-6">
          Si vous souhaitez mettre en ligne la page de cette collectivité,
          cochez la case.
        </item-description>
        <el-form-item prop="published" class="flex-1">
          <el-checkbox v-model="form.published">En ligne</el-checkbox>
        </el-form-item>
      </template>
      <template
        v-if="form.type == 'commune' && $store.getters.contextRole == 'admin'"
      >
        <div class="mb-6 flex text-xl text-gray-800">Organisation liée</div>
        <item-description container-class="mb-6">
          Si le statut de la collectivité est validée, les responsables de
          l'organisation ont accès au tableau de bord de la collectivité. Ils
          peuvent aussi modifier la page de la collectivité.
        </item-description>
        <el-form-item
          label="Id de l'organisation"
          prop="structure_id"
          class="flex-1"
        >
          <el-input v-model="form.structure_id" placeholder="ex: 4350" />
        </el-form-item>
        <el-form-item label="Statut" prop="state">
          <el-select v-model="form.state" placeholder="Sélectionner le statut">
            <el-option
              v-for="item in $store.getters.taxonomies.collectivities_states
                .terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </template>

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Enregistrer
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import {
  getCollectivity,
  addOrUpdateCollectivity,
  uploadImage,
} from '@/api/app'
import ItemDescription from '@/components/forms/ItemDescription'
import ImageField from '@/components/forms/ImageField.vue'

export default {
  name: 'CollectivityForm',
  components: { ItemDescription, ImageField },
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
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      if (vm.$store.getters.contextRole == 'admin') {
        return
      }
      if (
        vm.$store.getters.structure_as_responsable.collectivity.id !=
        to.params.id
      ) {
        vm.$router.push('/403')
      }
    })
  },
  data() {
    return {
      baseUrl: process.env.MIX_API_BASE_URL,
      loading: false,
      form: {
        type: 'department',
        zips: [],
      },
      model: 'collectivity',
      uploads: [],
    }
  },
  computed: {
    canEditField() {
      if (this.form.state != 'validated') {
        return true
      }
      if (this.$store.getters.contextRole == 'admin') {
        return true
      }
      return false
    },
    rules() {
      let rules = {
        name: [
          {
            required: true,
            message: 'Veuillez renseigner un nom de collectivité',
            trigger: 'blur',
          },
        ],
        type: [
          {
            required: true,
            message: 'Veuillez renseigner le type de collectivité',
            trigger: 'blur',
          },
        ],
      }

      if (this.form.type == 'department') {
        rules.department = [
          {
            required: true,
            message: 'Veuillez choisir un département',
            trigger: 'blur',
          },
        ]
      }

      if (this.form.type == 'commune') {
        rules.zips = [
          {
            required: true,
            message: 'Veuillez saisir les codes postaux de la commune',
            trigger: 'blur',
          },
        ]
      }

      return rules
    },
  },
  created() {
    if (this.mode == 'edit') {
      this.$store.commit('setLoading', true)
      getCollectivity(this.id)
        .then((response) => {
          this.form = response.data
          this.$store.commit('setLoading', false)
        })
        .catch(() => {
          this.loading = false
        })
    }
  },
  methods: {
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
    onSubmit() {
      this.loading = true
      this.$refs['collectivityForm'].validate((valid) => {
        if (valid) {
          addOrUpdateCollectivity(this.id, this.form)
            .then((response) => {
              this.form = response.data
              this.uploadImages()
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.loading = false
        }
      })
    },
    uploadImages() {
      const promises = []

      this.uploads.forEach((upload) => {
        promises.push(
          uploadImage(
            this.form.id,
            this.model,
            upload.blob,
            upload.cropSettings,
            upload.fieldName
          )
        )
      })
      Promise.all(promises).then(() => {
        this.onSubmitEnd()
      })
    },
    onSubmitEnd() {
      this.loading = false
      if (
        this.form.type == 'commune' &&
        this.$store.getters.contextRole == 'admin'
      ) {
        this.$router.push('/dashboard/collectivities')
      } else if (this.form.type == 'department') {
        this.$router.push('/dashboard/contents/departments')
      } else {
        // NO REDIRECT
      }

      this.$message({
        message: 'La collectivité a été enregistrée !',
        type: 'success',
      })
    },
  },
}
</script>
