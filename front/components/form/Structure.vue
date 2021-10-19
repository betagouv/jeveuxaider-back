<template>
  <el-form
    ref="structureForm"
    class=""
    :model="form"
    label-position="top"
    :rules="rules"
  >
    <div class="flex justify-between mb-6 text-1-5xl font-bold">
      <div class="text-[#242526]">Informations générales</div>
      <div v-if="form.rna && form.rna != 'N/A'" class="text-gray-400">
        <span class="font-medium">RNA</span> {{ form.rna }}
      </div>
    </div>

    <el-form-item label="Nom de votre organisation" prop="name">
      <el-input v-model="form.name" placeholder="Nom de votre organisation" />
    </el-form-item>

    <el-form-item
      v-if="
        $store.getters.contextRole == 'admin' &&
        form.statut_juridique == 'Association'
      "
      label="RNA"
      prop="rna"
    >
      <el-input v-model="form.rna" placeholder="Numéro RNA" />
    </el-form-item>

    <el-form-item
      v-if="
        $store.getters.contextRole == 'admin' &&
        form.statut_juridique == 'Association'
      "
      label="API ID Établissement"
      prop="api_id"
    >
      <el-input
        v-model="form.api_id"
        placeholder="Numéro d'établissement sur l'API"
      />
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
      label="Disposez vous d'agréments ?"
      prop="association_types"
    >
      <el-select
        v-model="form.association_types"
        placeholder="Choix des agréments"
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
      v-if="form.statut_juridique != 'Collectivité'"
      label="Domaines d'action"
      prop="domaines"
      class=""
    >
      <el-checkbox-group
        v-model="domainesSelected"
        size="medium"
        class="custom-checkbox"
      >
        <el-checkbox
          v-for="domaine in domaines"
          :key="domaine.id"
          :label="domaine.name.fr"
          class="!bg-white"
          border
          :checked="isDomaineSelected(domaine.id)"
          @change="handleClickDomaine(domaine)"
        ></el-checkbox>
      </el-checkbox-group>
    </el-form-item>
    <el-form-item
      v-if="form.statut_juridique != 'Collectivité'"
      label="Publics bénéficiaires"
      prop="publics_beneficiaires"
      class=""
    >
      <el-checkbox-group
        v-model="form.publics_beneficiaires"
        size="medium"
        class="custom-checkbox"
      >
        <el-checkbox
          v-for="item in $store.getters.taxonomies.mission_publics_beneficiaires
            .terms"
          :key="item.value"
          :label="item.value"
          class="!bg-white"
          border
          >{{ item.label }}</el-checkbox
        >
      </el-checkbox-group>
    </el-form-item>

    <el-form-item
      label="À propos de votre organisation (200 caractères min.)"
      prop="description"
      class="flex-1"
    >
      <el-input
        v-model="form.description"
        type="textarea"
        rows="4"
        placeholder="Dites-nous tout à propos de votre organisation"
      />
    </el-form-item>

    <el-form-item label="E-mail public de votre organisation" prop="email">
      <el-input
        v-model="form.email"
        placeholder="contact@mon-organisation.fr"
      />
    </el-form-item>

    <el-form-item label="Téléphone de votre organisation" prop="phone">
      <el-input v-model="form.phone" placeholder="01 23 45 67 89" />
    </el-form-item>

    <div class="mt-12 mb-6 flex text-[#242526] text-1-5xl font-bold">
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

    <div class="mt-12 mb-6 flex text-[#242526] text-1-5xl font-bold">
      Votre organisation sur les réseaux
    </div>
    <el-form-item label="Site de l'organisation" prop="website">
      <el-input v-model="form.website" placeholder="https://www.votresite.fr" />
    </el-form-item>
    <el-form-item label="Page Facebook" prop="facebook">
      <el-input
        v-model="form.facebook"
        placeholder="https://facebook.com/votrepage"
      />
    </el-form-item>
    <el-form-item label="Page Twitter" prop="twitter">
      <el-input
        v-model="form.twitter"
        placeholder="https://twitter.com/votrepage"
      />
    </el-form-item>
    <el-form-item label="Profil Instagram" prop="instagram">
      <el-input
        v-model="form.instagram"
        placeholder="https://instagram.com/votrepage"
      />
    </el-form-item>
    <el-form-item label="Plateforme de don" prop="donation">
      <el-input
        v-model="form.donation"
        placeholder="URL de votre page (Helloasso, Microdon, Ulule, etc...)"
      />
    </el-form-item>

    <div class="mt-12 mb-6 text-[#242526] text-1-5xl font-bold">
      Visuels & personnalisation
    </div>

    <ImageField
      model="structure"
      :model-id="form.id"
      :max-size="2000000"
      :preview-width="'200px'"
      :field="form.logo"
      :aspect-ratio="0"
      label="Logo"
      field-name="logo"
      :min-width="120"
      component-classes="mb-8"
      @add-or-crop="handleAddOrCrop($event)"
      @delete="handleDelete($event)"
    />

    <!-- Visuel N° 1 et 2 -->
    <template v-if="form.statut_juridique == 'Association'">
      <div class="mb-8">
        <div class="el-form-item__label">Visuel N° 1</div>
        <template
          v-if="
            $store.getters.contextRole != 'admin' && form['override_image_1']
          "
        >
          <ItemDescription container-class="mb-6">
            Cette image a été surchargée par un administrateur et n'est pas
            modifiable.
          </ItemDescription>

          <img
            :src="form.override_image_1.thumb"
            class="opacity-50"
            width="250px"
          />
        </template>

        <div v-else class="relative inline-flex flex-col group">
          <img
            :src="`/images/organisations/domaines/${selectedImages[0]}.jpg`"
            :srcset="`/images/organisations/domaines/${selectedImages[0]}@2x.jpg 2x`"
            class="h-auto rounded-lg cursor-pointer shadow-xl"
            width="250px"
            @click="onEditImageClick(0)"
          />
          <div
            class="z-1 absolute flex justify-center items-center w-8 h-8 text-[#070191] bg-white rounded-full opacity-75 group-hover:opacity-100 pointer-events-none"
            style="right: 12px; bottom: 12px"
          >
            <div
              class="text-[#070191]"
              v-html="
                require('@/assets/images/icones/heroicon/edit.svg?include')
              "
            />
          </div>
        </div>
      </div>

      <ImageField
        v-if="$store.getters.contextRole === 'admin'"
        model="structure"
        :model-id="form.id ? form.id : null"
        :max-size="2000000"
        :min-width="1440"
        :min-height="1080"
        :aspect-ratio="1440 / 1080"
        :field="form[`override_image_1`]"
        :field-name="`override_image_1`"
        label="Surcharger Visuel N° 1"
        component-classes="mb-8"
        @add-or-crop="handleAddOrCrop($event)"
        @delete="handleDelete($event)"
      ></ImageField>

      <div class="mb-8">
        <div class="el-form-item__label">Visuel N° 2</div>

        <template
          v-if="
            $store.getters.contextRole != 'admin' && form['override_image_2']
          "
        >
          <ItemDescription container-class="mb-6">
            Cette image a été surchargée par un administrateur et n'est pas
            modifiable.
          </ItemDescription>

          <img
            :src="form.override_image_2.thumb"
            class="opacity-50"
            width="250px"
          />
        </template>

        <div v-else class="relative inline-flex group">
          <img
            :src="`/images/organisations/domaines/${selectedImages[1]}.jpg`"
            :srcset="`/images/organisations/domaines/${selectedImages[1]}@2x.jpg 2x`"
            class="h-auto rounded-lg cursor-pointer shadow-xl"
            width="250px"
            @click="onEditImageClick(1)"
          />
          <div
            class="z-1 absolute flex justify-center items-center w-8 h-8 text-[#070191] bg-white rounded-full opacity-75 group-hover:opacity-100 pointer-events-none"
            style="right: 12px; bottom: 12px"
          >
            <div
              class="text-[#070191]"
              v-html="
                require('@/assets/images/icones/heroicon/edit.svg?include')
              "
            />
          </div>
        </div>
      </div>

      <ImageField
        v-if="$store.getters.contextRole === 'admin'"
        model="structure"
        :model-id="form.id ? form.id : null"
        :max-size="2000000"
        :min-width="1440"
        :min-height="1080"
        :aspect-ratio="1440 / 1080"
        :field="form[`override_image_2`]"
        :field-name="`override_image_2`"
        label="Surcharger Visuel N° 2"
        component-classes="mb-8"
        @add-or-crop="handleAddOrCrop($event)"
        @delete="handleDelete($event)"
      ></ImageField>

      <DialogOrganisationImagesPicker
        :initial-image="selectedImages[imageIndex]"
        :domaines="form.domaines.length ? form.domaines : domaines"
        :is-visible="showDialog"
        @picked="onPickedImage"
        @close="showDialog = false"
      />
    </template>

    <el-form-item
      v-if="
        $store.getters.contextRole === 'admin' &&
        form.statut_juridique == 'Association'
      "
      label="Couleur"
      prop="color"
    >
      <el-select v-model="form.color" placeholder="Couleur">
        <el-option
          v-for="item in colors"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        >
          <div class="flex items-center">
            <span
              class="w-6 h-6 rounded-full mr-4 my-auto"
              :style="`float: left; background-color: ${item.value};`"
            ></span>
            <span>{{ item.label }}</span>
          </div>
        </el-option>
      </el-select>
    </el-form-item>

    <div
      v-if="$store.getters.contextRole === 'admin'"
      class="bg-red-100 p-4 rounded-[10px] mb-8"
    >
      <el-form-item prop="send_volunteer_coordonates" class="flex-1 !mb-0">
        <el-checkbox v-model="form.send_volunteer_coordonates">
          <span> Inclure les coordonnées des participants dans les mails </span>
        </el-checkbox>
      </el-form-item>
    </div>

    <div class="flex pt-2 items-center">
      <el-button type="primary" :loading="loading" @click="onSubmit">
        Enregistrer
      </el-button>
      <div
        v-if="$store.getters.contextRole === 'responsable'"
        class="text-[#f56565] ml-4 cursor-pointer hover:underline"
        @click="onSubmitDelete"
      >
        Supprimer mon organisation
      </div>
    </div>
  </el-form>
</template>

<script>
import FormMixin from '@/mixins/Form'
import FormWithAddress from '@/mixins/FormWithAddress'

export default {
  mixins: [FormMixin, FormWithAddress],
  props: {
    structure: {
      type: Object,
      default() {
        return { domaines: [], publics_beneficiaires: [] }
      },
    },
    domaines: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.structure },
      uploads: [],
      imageIndex: 0,
      showDialog: false,
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
        domaines: {
          required: true,
          message: "Veuillez choisir au moins un domaine d'action",
          trigger: 'blur',
        },
        publics_beneficiaires: {
          required: true,
          message: 'Veuillez choisir au moins un public bénéficiaire',
          trigger: 'blur',
        },
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
        email: [
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
        ],
        phone: [
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
            trigger: 'blur',
          },
        ],
        website: [
          {
            pattern: /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_+.~#?&//=]*)*$/,
            message:
              "Le lien n'est pas correct. Il doit être au format https://www.votresite.fr",
            trigger: 'blur',
          },
        ],
        facebook: [
          {
            pattern: /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_+.~#?&//=]*)*$/,
            message:
              "Le lien n'est pas correct. Il doit être au format https://facebook.com/votrepage",
            trigger: 'blur',
          },
        ],
        twitter: [
          {
            pattern: /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_+.~#?&//=]*)*$/,
            message:
              "Le lien n'est pas correct. Il doit être au format https://twitter.com/votrepage",
            trigger: 'blur',
          },
        ],
        instagram: [
          {
            pattern: /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_+.~#?&//=]*)*$/,
            message:
              "Le lien n'est pas correct. Il doit être au format https://instagram.com/votrepage",
            trigger: 'blur',
          },
        ],
        donation: [
          {
            pattern: /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_+.~#?&//=]*)*$/,
            message:
              "Le lien n'est pas correct. Il doit être au format https://votrepagededonation.fr",
            trigger: 'blur',
          },
        ],
      },
      logo: null,
      colors: [
        { label: 'Noir', value: '#111827' },
        { label: 'Gris', value: '#4B5563' },
        { label: 'Rouge', value: '#B91C1C' },
        { label: 'Orange', value: '#D97706' },
        { label: 'Vert', value: '#047857' },
        { label: 'Bleu', value: '#1E40AF' },
        { label: 'Indigo', value: '#3730A3' },
        { label: 'Violet', value: '#5B21B6' },
        { label: 'Rose', value: '#DB2777' },
      ],
    }
  },
  computed: {
    domainesSelected: {
      get() {
        return this.form.domaines.map((item) => item.name.fr)
      },
      set(items) {
        //
      },
    },
    selectedImages() {
      return this.form.image_1
        ? [this.form.image_1, this.form.image_2]
        : this.form.domaines.length > 0
        ? [this.form.domaines[0].id + '_1', this.form.domaines[0].id + '_2']
        : ['1_1', '2_1']
    },
  },
  methods: {
    isDomaineSelected(id) {
      return this.form.domaines.filter((item) => item.id == id).length > 0
    },
    handleClickDomaine(domaine) {
      if (this.isDomaineSelected(domaine.id)) {
        this.form.domaines = this.form.domaines.filter(
          (item) => item.id !== domaine.id
        )
      } else {
        this.$set(this.form, 'domaines', [...this.form.domaines, domaine])
      }
    },
    onEditImageClick(index) {
      this.imageIndex = index
      this.showDialog = true
    },
    onPickedImage(imageName) {
      this.selectedImages[this.imageIndex] = imageName
      this.form[`image_${this.imageIndex + 1}`] = imageName
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
    onSubmit() {
      this.loading = true
      this.$refs.structureForm.validate(async (valid, fields) => {
        if (valid) {
          await this.uploadImages()
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
          this.showErrors(fields)
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
      ).then(async () => {
        this.form.state = 'Désinscrite'
        await this.$api.updateStructure(this.form.id, this.form)
        await this.$store.dispatch('auth/fetchUser')
        this.$message.success({
          message: `Votre organisation ${this.form.name} a bien été supprimée.`,
        })
        this.$router.push('/')
      })
    },
    uploadImages() {
      if (this.form.id) {
        const promises = []
        this.uploads.forEach((upload) => {
          promises.push(
            this.$api.uploadImage(
              this.form.id,
              'structure',
              upload.blob,
              upload.cropSettings,
              upload.fieldName
            )
          )
        })
        Promise.all(promises)
      }
    },
  },
}
</script>
