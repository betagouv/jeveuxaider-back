<template>
  <div
    v-if="!$store.getters.loading"
    class="profile-form max-w-2xl pl-12 pb-12"
  >
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">
        Collectivité
      </div>
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
      <div class="mb-6 text-xl text-gray-800">
        Informations générales
      </div>

      <el-form-item label="Nom de la collectivité" prop="title">
        <el-input v-model="form.title" placeholder="Nom de la collectivité" />
        <item-description
          >Accessible à l'adresse : {{ baseUrl }}/territoires/{{
            form.title | slugify
          }}</item-description
        >
      </el-form-item>

      <el-form-item label="Type" prop="type">
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
        v-if="form.type == 'commune'"
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

      <div class="mb-6">
        <div class="mb-6 text-xl text-gray-800">
          Photo de la collectivité
        </div>
        <item-description>
          Résolution minimale: {{ imgMinWidth }} par
          {{ imgMinHeight }} pixels<br />
          Taille maximale: {{ imgMaxSize | prettyBytes }}
        </item-description>

        <div v-show="imgPreview">
          <div class="preview-area">
            <img :src="imgPreview" alt="Cropped Image" />
          </div>

          <div class="actions mt-4">
            <el-button
              type="secondary"
              @click.prevent="dialogCropVisible = true"
            >
              Recadrer
            </el-button>
            <el-button
              type="danger"
              icon="el-icon-delete"
              :loading="loadingDelete"
              @click.prevent="onDelete()"
            >
              Supprimer
            </el-button>
          </div>

          <el-dialog
            title="Recadrer"
            :visible.sync="dialogCropVisible"
            width="680"
          >
            <vue-cropper
              ref="cropper"
              :src="imgSrc ? imgSrc : form.image ? form.image.original : null"
              :aspect-ratio="8 / 3"
              :zoomable="false"
              :movable="false"
              :zoom-on-touch="false"
              :zoom-on-wheel="false"
              :auto-crop-area="1"
              :min-container-height="240"
              :min-container-width="640"
              preview=".preview"
              @cropmove="ensureMinWidth"
            />
            <span slot="footer" class="dialog-footer">
              <el-button @click="onReset()">Réinitialiser</el-button>
              <el-button @click="dialogCropVisible = false">Annuler</el-button>
              <el-button type="primary" :loading="loadingCrop" @click="onCrop()"
                >Valider</el-button
              >
            </span>
          </el-dialog>
        </div>
        <div v-show="!imgPreview">
          <el-upload
            class="upload-demo"
            drag
            action=""
            :show-file-list="false"
            :auto-upload="false"
            :on-change="onSelectFile"
          >
            <i class="el-icon-upload" />
            <div class="el-upload__text">
              Glissez votre image ou <br /><em
                >cliquez ici pour la sélectionner</em
              >
            </div>
          </el-upload>
        </div>
      </div>

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
import Crop from '@/mixins/Crop'

export default {
  name: 'CollectivityForm',
  components: { ItemDescription },
  mixins: [Crop],
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
      baseUrl: process.env.MIX_API_BASE_URL,
      loading: false,
      form: {
        type: 'department',
      },
      model: 'collectivity',
      imgMinWidth: 1600,
      imgMinHeight: 600,
      imgMaxSize: 4000000, // 4 MB
    }
  },
  computed: {
    rules() {
      let rules = {
        title: [
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
      this.$refs['collectivityForm'].validate((valid) => {
        if (valid) {
          addOrUpdateCollectivity(this.id, this.form)
            .then((response) => {
              this.form = response.data
              if (this.img) {
                let cropSettings = this.$refs.cropper
                  ? this.$refs.cropper.getData()
                  : null
                uploadImage(
                  this.form.id,
                  this.model,
                  this.img,
                  cropSettings
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
      this.$router.push('/dashboard/contents/collectivities')
      this.$message({
        message: 'La collectivité a été enregistrée !',
        type: 'success',
      })
    },
  },
}
</script>
