<template>
  <el-form
    ref="reseauForm"
    class=""
    :model="form"
    label-position="top"
    :rules="rules"
  >
    <div class="flex justify-between mb-6 text-1-5xl font-bold">
      <div class="text-[#242526]">Informations</div>
    </div>

    <el-form-item label="Nom du réseau" prop="name">
      <el-input v-model="form.name" placeholder="Ex: Croix-Rouge" />
    </el-form-item>

    <el-form-item label="Description" prop="description" class="flex-1">
      <el-input
        v-model="form.description"
        type="textarea"
        rows="4"
        placeholder="Décrivez succinctement le réseau"
      />
    </el-form-item>

    <el-form-item
      v-if="domaines.length > 0"
      label="Domaines d'action"
      prop="domaines"
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
        >
        </el-checkbox>
      </el-checkbox-group>
    </el-form-item>

    <el-form-item label="Publics bénéficiaires" prop="publics_beneficiaires">
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
        >
          {{ item.label }}
        </el-checkbox>
      </el-checkbox-group>
    </el-form-item>

    <div class="mt-12 mb-6 flex text-[#242526] text-1-5xl font-bold">
      Contact
    </div>

    <div class="grid grid-cols-2 gap-2">
      <el-form-item label="E-mail" prop="email">
        <el-input
          v-model="form.email"
          placeholder="contact@mon-organisation.fr"
        />
      </el-form-item>

      <el-form-item label="Téléphone" prop="phone">
        <el-input v-model="form.phone" placeholder="01 23 45 67 89" />
      </el-form-item>
    </div>

    <div class="mt-12 mb-6 flex text-[#242526] text-1-5xl font-bold">Lieu</div>

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

    <div class="grid grid-cols-2 gap-2">
      <el-form-item label="Code postal" prop="zip">
        <el-input v-model="form.zip" disabled placeholder="Code postal" />
      </el-form-item>

      <el-form-item label="Ville" prop="city">
        <el-input v-model="form.city" disabled placeholder="Ville" />
      </el-form-item>
    </div>

    <div class="hidden">
      <el-form-item label="Latitude" prop="latitude">
        <el-input v-model="form.latitude" disabled placeholder="Latitude" />
      </el-form-item>

      <el-form-item label="Longitude" prop="longitude">
        <el-input v-model="form.longitude" disabled placeholder="Longitude" />
      </el-form-item>
    </div>

    <div class="mt-12 mb-6 flex text-[#242526] text-1-5xl font-bold">
      Réseaux sociaux
    </div>

    <div class="grid grid-cols-2 gap-2">
      <el-form-item label="Site de l'organisation" prop="website">
        <el-input
          v-model="form.website"
          placeholder="https://www.votresite.fr"
        />
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
    </div>

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
      model="reseaux"
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

    <!-- Visuel 1 -->
    <div class="mb-8">
      <div class="el-form-item__label">Visuel N° 1</div>
      <template
        v-if="$store.getters.contextRole != 'admin' && form['override_image_1']"
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
            v-html="require('@/assets/images/icones/heroicon/edit.svg?raw')"
          />
        </div>
      </div>
    </div>

    <!-- Visuel 2 -->
    <div class="mb-8">
      <div class="el-form-item__label">Visuel N° 2</div>

      <template
        v-if="$store.getters.contextRole != 'admin' && form['override_image_2']"
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
            v-html="require('@/assets/images/icones/heroicon/edit.svg?raw')"
          />
        </div>
      </div>
    </div>

    <DialogOrganisationImagesPicker
      :initial-image="selectedImages[imageIndex]"
      :domaines="form.domaines.length ? form.domaines : domaines"
      :is-visible="showDialog"
      @picked="onPickedImage"
      @close="showDialog = false"
    />

    <div class="flex pt-2 items-center">
      <el-button type="primary" :loading="loading" @click="onSubmit">
        Enregistrer
      </el-button>
    </div>
  </el-form>
</template>

<script>
import FormMixin from '@/mixins/Form'
import FormWithAddress from '@/mixins/FormWithAddress'

export default {
  mixins: [FormMixin, FormWithAddress],
  props: {
    reseau: {
      type: Object,
      default() {},
    },
  },
  data() {
    return {
      loading: false,
      domaines: [],
      uploads: [],
      form: { ...this.reseau },
      rules: {
        name: [
          {
            required: true,
            message: 'Veuillez renseigner un nom',
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
        description: [
          {
            required: true,
            message: 'La description est requise',
            trigger: 'change',
          },
          {
            min: 150,
            message: 'La description doit faire au moins 150 caractères',
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
      },
      showDialog: false,
      imageIndex: 0,
    }
  },
  async fetch() {
    const { data: domaines } = await this.$api.fetchTags({
      'filter[type]': 'domaine',
    })
    this.domaines = domaines.data

    if (!this.form.image_1) {
      this.form.image_1 = this.firstImage
    }
    if (!this.form.image_2) {
      this.form.image_2 = this.secondImage
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
    firstImage() {
      // return this.form.image_1 ?? '1_1'
      return (
        this.form.image_1 ??
        (this.form.domaines.length > 0
          ? this.form.domaines[0].id + '_1'
          : '1_1')
      )
    },
    secondImage() {
      return this.form.image_2 ?? this.form.domaines.length > 0
        ? this.form.domaines[0].id + '_2'
        : '2_1'
    },
    selectedImages() {
      return [this.firstImage, this.secondImage]
    },
  },
  methods: {
    onSubmit() {
      this.loading = true

      this.$refs.reseauForm.validate(async (valid, fields) => {
        if (valid) {
          await this.uploadImages()

          this.$api
            .addOrUpdateReseau(this.form)
            .then(() => {
              this.loading = false
              this.$router.push('/dashboard/reseaux')
              this.$message({
                message: 'Le réseau a été enregistré !',
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
    isDomaineSelected(id) {
      return this.form.domaines.some((item) => item.id == id)
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
    uploadImages() {
      if (this.form.id) {
        const promises = []
        this.uploads.forEach((upload) => {
          promises.push(
            this.$api.uploadImage(
              this.form.id,
              'reseaux',
              upload.blob,
              upload.cropSettings,
              upload.fieldName
            )
          )
        })
        Promise.all(promises)
      }
    },
    onEditImageClick(index) {
      this.imageIndex = index
      this.showDialog = true
    },
    onPickedImage(imageName) {
      // this.selectedImages[this.imageIndex] = imageName
      this.$set(this.form, `image_${this.imageIndex + 1}`, imageName)
    },
  },
}
</script>
