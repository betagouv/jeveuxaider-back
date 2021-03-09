<template>
  <el-form
    ref="structureForm"
    class="max-w-2xl"
    :model="form"
    label-position="top"
    :rules="rules"
  >
    <div class="mb-6 text-xl text-gray-800">Informations générales</div>

    <el-form-item label="Nom de votre organisation" prop="name">
      <el-input v-model="form.name" placeholder="Nom de votre organisation" />
    </el-form-item>

    <el-form-item label="Statut juridique" prop="statut_juridique">
      <el-select v-model="form.statut_juridique" placeholder="Statut juridique">
        <el-option
          v-for="item in $store.getters.taxonomies.structure_legal_status.terms"
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
          v-for="item in $store.getters.taxonomies.structure_publique_etat_types
            .terms"
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
        placeholder="Choisissez le type de structure privée"
      >
        <el-option
          v-for="item in $store.getters.taxonomies.structure_privee_types.terms"
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

    <div class="mt-12 mb-6 flex text-xl text-gray-800">
      Réseau national ou territorial
    </div>
    <ItemDescription container-class="mb-6">
      Si votre organisation est membre d'un réseau national ou territorial qui
      figure dans le menu déroulant du champ ci-dessous, sélectionnez-le. Vous
      permettrez au superviseur de votre réseau de visualiser les missions et
      bénévoles rattachés à votre organisation. Vous faciliterez également la
      validation de votre organisation par les autorités territoriales lors de
      votre inscription.
    </ItemDescription>
    <el-form-item label="Réseau national" prop="reseau" class="flex-1">
      <el-select
        v-model="form.reseau_id"
        clearable
        filterable
        placeholder="Réseau national"
      >
        <el-option
          v-for="item in $store.getters.reseaux"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        />
      </el-select>
    </el-form-item>

    <div class="mt-12 mb-6 flex text-xl text-gray-800">
      Lieu de l'établissement
    </div>
    <el-form-item label="Département" prop="department">
      <el-select v-model="form.department" filterable placeholder="Département">
        <el-option
          v-for="item in $store.getters.taxonomies.departments.terms"
          :key="item.value"
          :label="`${item.value} - ${item.label}`"
          :value="item.value"
        />
      </el-select>
    </el-form-item>
    <AlgoliaPlacesInput
      :value="form.full_address"
      @selected="setAddress"
      @clear="clearAddress"
    />
    <el-form-item label="Adresse" prop="address">
      <el-input v-model="form.address" disabled placeholder="Adresse" />
    </el-form-item>
    <div class="flex">
      <el-form-item label="Code postal" prop="zip" class="flex-1 mr-2">
        <el-input v-model="form.zip" disabled placeholder="Code postal" />
      </el-form-item>
      <el-form-item label="Ville" prop="city" class="flex-1">
        <el-input v-model="form.city" disabled placeholder="Ville" />
      </el-form-item>
    </div>
    <div class="hidden">
      <el-form-item label="Latitude" prop="latitude" class="flex-1 mr-2">
        <el-input v-model="form.latitude" disabled placeholder="Latitude" />
      </el-form-item>
      <el-form-item label="Longitude" prop="longitude" class="flex-1">
        <el-input v-model="form.longitude" disabled placeholder="Longitude" />
      </el-form-item>
    </div>
    <div class="flex pt-2 items-center">
      <el-button type="primary" :loading="loading" @click="onSubmit">
        Enregistrer
      </el-button>
      <div
        v-if="$store.getters.contextRole === 'responsable'"
        class="text-red-500 ml-4 cursor-pointer hover:underline"
        @click="onSubmitDelete"
      >
        Supprimer mon organisation
      </div>
    </div>
  </el-form>
</template>

<script>
import FormWithAddress from '@/mixins/FormWithAddress'

export default {
  mixins: [FormWithAddress],
  props: {
    structure: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.structure },
      rules: {
        name: [
          {
            required: true,
            message: "Veuillez renseigner un nom d'organisation",
            trigger: 'blur',
          },
        ],
        statut_juridique: [
          {
            required: true,
            message: 'Veuillez choisir un statut juridique',
            trigger: 'blur',
          },
        ],
        department: {
          required: true,
          message: 'Le champ département est requis',
          trigger: 'blur',
        },
        address: [
          {
            required: true,
            message: 'Veuillez renseigner une adresse',
            trigger: 'blur',
          },
        ],
        city: [
          {
            required: true,
            message: 'Veuillez renseigner un ville',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  created() {
    if (this.mode == 'edit') {
      this.$store.commit('setLoading', true)
      this.$api
        .getStructure(this.$route.params.id)
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
    uploadLogo(request) {
      const reader = new FileReader()
      reader.readAsDataURL(request.file)
      reader.onload = (e) => {
        this.loading = true
        this.$api
          .addOrUpdateStructure(this.structure.id, { logo: e.target.result })
          .then((response) => {
            this.form = response.data
            this.loading = false
            this.$message({
              message: 'Le logo a été enregistré !',
              type: 'success',
            })
          })
          .catch(() => {
            this.loading = false
          })
      }
    },
    beforeLogoUpload(file) {
      const isLt5M = file.size / 1024 / 1024 < 4
      if (!isLt5M) {
        this.$message({
          message: 'Votre image ne doit pas éxcéder une taille de 4MB',
          type: 'error',
        })
      }
      return isLt5M
    },
    onSubmit() {
      this.loading = true
      this.$refs.structureForm.validate((valid) => {
        if (valid) {
          this.$api
            .addOrUpdateStructure(this.structure.id, this.form)
            .then(() => {
              this.loading = false
              this.$router.go(-1)
              this.$message({
                message: "L'organisation a été enregistrée !",
                type: 'success',
              })
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.loading = false
        }
      })
    },
    onSubmitDelete() {
      this.$confirm(
        'Souhaitez-vous réellement supprimer votre organisation de JeVeuxAider.gouv.fr ?',
        'Supprimer mon organisation',
        {
          confirmButtonText: 'Je confirme',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.form.state = 'Désinscrite'
        this.$api.updateStructure(this.form.id, this.form).then(() => {
          this.$message({
            type: 'success',
            message: `Votre organisation ${this.form.name} a bien été supprimée.`,
          })
          this.$router.push('/')
        })
      })
    },
  },
}
</script>
