<template>
  <div class="register-step">
    <portal to="register-steps-help">
      <p>
        Dites-nous en plus sur votre organisation !
        <br />Ces
        <span class="font-bold">informations générales</span> permettront au
        service référent de mieux vous connaître.
      </p>
      <p>
        Une question? Contactez<br /><span class="font-bold"
          ><a
            target="_blank"
            href="mailto:contact@reserve-civique.on.crisp.email"
          >
            le support</a
          >
        </span>
        ou
        <button onclick="$crisp.push(['do', 'chat:open'])">
          chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps :active="2" align-center class="p-4 sm:p-8 border-b-2">
      <el-step
        title="Profil"
        description="Je complète les informations de mon profil"
      />
      <el-step
        title="Organisation"
        description="J'enregistre mon organisation en tant que responsable"
      />
      <el-step
        title="Adresse"
        description="J'enregistre le lieu de mon établissement"
      />
    </el-steps>
    <div class="p-4 sm:p-12">
      <div class="font-bold text-2xl text-gray-800 mb-6">
        Mon organisation
      </div>
      <!-- TODO -->
      <!-- <div class="text-label pl-0 pb-2 mt-6" style="padding-left: 0">
        Logo de l'organisation
      </div> -->
      <div v-show="false" class="mb-10">
        <div class="flex -m-4">
          <div class="m-4">
            <div v-if="logoPreview" class="h-32 w-32 flex items-center">
              <img :src="logoPreview" alt="Logo" />
            </div>
            <div
              v-else
              class="default-picture h-32 w-32 font-bold flex items-center justify-center text-white text-2xl bg-primary"
            >
              LOGO
            </div>
          </div>
          <div class="m-4">
            <el-upload
              ref="logo"
              action=""
              :http-request="uploadLogo"
              accept="image/*"
              :before-upload="beforeLogoUpload"
              :auto-upload="false"
              :on-change="onChangeLogo"
            >
              <el-button>Modifier</el-button>
              <div slot="tip" class="el-upload__tip text-xs">
                Nous acceptons les fichiers au format PNG, JPG ou GIF, d'une
                taille maximale de 5 Mo
              </div>
            </el-upload>
          </div>
        </div>
      </div>
      <!-- END TODO -->
      <el-form
        ref="structureForm"
        :model="form"
        label-position="top"
        :rules="rules"
        class="max-w-lg"
      >
        <el-form-item label="Nom de votre organisation" prop="name">
          <el-input
            v-model="form.name"
            placeholder="Nom de votre organisation"
          />
        </el-form-item>
        <el-form-item label="Statut juridique" prop="statut_juridique">
          <el-select
            v-model="form.statut_juridique"
            placeholder="Statut juridique"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.structure_legal_status
                .terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="form.statut_juridique == 'Association'"
          label="Disposez vous d'un agrément ?"
          prop="association_types"
        >
          <el-select
            v-model="form.association_types"
            placeholder="Choix de l'agrément"
            multiple
          >
            <el-option
              v-for="item in $store.getters.taxonomies.association_types.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="form.statut_juridique == 'Structure publique'"
          prop="structure_publique_type"
        >
          <el-select
            v-model="form.structure_publique_type"
            placeholder="Choisissez le type de votre organisation publique"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.structure_publique_types
                .terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="
            form.statut_juridique == 'Structure publique' &&
            (form.structure_publique_type == 'Service de l\'Etat' ||
              form.structure_publique_type == 'Etablissement public')
          "
          prop="structure_publique_etat_type"
        >
          <el-select
            v-model="form.structure_publique_etat_type"
            placeholder="Choisissez une option"
          >
            <el-option
              v-for="item in $store.getters.taxonomies
                .structure_publique_etat_types.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          v-if="form.statut_juridique == 'Structure privée'"
          prop="structure_privee_type"
        >
          <el-select
            v-model="form.structure_privee_type"
            placeholder="Choisissez le type d'organisation privée"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.structure_privee_types
                .terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>

        <el-form-item
          label="Présentation synthétique de l'organisation"
          prop="description"
          class="flex-1"
        >
          <el-input
            v-model="form.description"
            type="textarea"
            :autosize="{ minRows: 2, maxRows: 6 }"
            placeholder="Décrivez votre organisation, en quelques mots"
          />
        </el-form-item>

        <div class="mb-6 mt-12 flex text-xl text-gray-800">
          Réseau national ou territorial
        </div>
        <item-description>
          Si votre organisation est membre d'un réseau national ou territorial
          qui figure dans le menu déroulant du champ ci-dessous,
          sélectionnez-le. Vous permettrez au superviseur de votre réseau de
          visualiser les missions et bénévoles rattachés à votre organisation.
          Vous faciliterez également la validation de votre organisation par les
          autorités territoriales lors de votre inscription.
        </item-description>
        <el-form-item label="Réseau national" prop="reseau" class="flex-1">
          <el-select v-model="form.reseau_id" clearable placeholder="Aucun">
            <el-option
              v-for="item in reseauxOptions"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Continuer
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import { addOrUpdateStructure, updateStructureLogo } from '@/api/structure'
import ItemDescription from '@/components/forms/ItemDescription'

export default {
  name: 'StructureStep',
  components: { ItemDescription },
  data() {
    return {
      loading: false,
      structureId: null,
      form: {},
      logoPreview: null,
      rules: {
        name: {
          required: true,
          message: 'Le nom de votre organisation est requis',
          trigger: 'blur',
        },
        statut_juridique: {
          required: true,
          message:
            'Veuillez renseigner la forme juridique de votre organisation',
          trigger: 'blur',
        },
        mobile: [
          {
            required: true,
            message: 'Un numéro de téléphone est obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
            trigger: 'blur',
          },
        ],
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message: 'Le format du numéro de téléphone est incorrect',
          trigger: 'blur',
        },
      },
    }
  },
  computed: {
    reseauxOptions() {
      return this.$store.getters.reseaux
    },
  },
  created() {
    this.structureId = this.$store.getters.structure_as_responsable
      ? this.$store.getters.structure_as_responsable.id
      : null
  },
  methods: {
    uploadLogo(request) {
      updateStructureLogo(this.structureId, request.file)
        .then(() => {
          this.loading = false
          // Get profile to get new role
          this.$store.dispatch('user/get')
          this.$router.push('/register/responsable/step/address')
        })
        .catch(() => {
          this.loading = false
        })
    },
    beforeLogoUpload(file) {
      const isLt5M = file.size / 1024 / 1024 < 5
      if (!isLt5M) {
        this.$message({
          message: 'Votre image ne doit pas éxcéder une taille de 4MB',
          type: 'error',
        })
        this.loading = false
        this.logoPreview = null
      }
      return isLt5M
    },
    onChangeLogo(file) {
      var reader = new FileReader()
      reader.readAsDataURL(file.raw)
      reader.onload = (e) => {
        this.logoPreview = e.target.result
      }
    },
    onSubmit() {
      this.loading = true
      this.$refs['structureForm'].validate((valid) => {
        if (valid) {
          addOrUpdateStructure(this.structureId, this.form)
            .then(async (response) => {
              this.structureId = response.data.id
              if (this.$refs.logo.uploadFiles.length > 0) {
                this.$refs.logo.submit()
              } else {
                // Get profile to get new role
                await this.$store.dispatch('user/get')
                this.$router.push('/register/responsable/step/address')
                this.loading = false
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
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-step__description
    @apply hidden
    @screen sm
        @apply block
::v-deep .el-upload-list__item-name
    @apply hidden
</style>
