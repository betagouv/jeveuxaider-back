<template>
  <el-form
    ref="thematiqueForm"
    :model="form"
    label-position="top"
    :rules="rules"
  >
    <div class="mb-6 text-xl text-gray-800">Informations générales</div>
    <el-form-item label="Nom du domaine d'action" prop="name">
      <el-input
        v-model="form.name"
        placeholder="Ex: Solidarité et instertion"
      />
      <ItemDescription>
        Accessible à l'adresse : {{ $config.appUrl }}/domaines-action/{{
          form.name | slugify
        }}
      </ItemDescription>
    </el-form-item>
    <el-form-item label="Titre du domaine d'action" prop="title">
      <el-input
        v-model="form.title"
        placeholder="Ex: Rejoignez JeVeuxAider.gouv.fr pour la solidarité et l'insertion"
      />
    </el-form-item>
    <el-form-item label="Description" prop="description" class="flex-1">
      <el-input
        v-model="form.description"
        type="textarea"
        :autosize="{ minRows: 2, maxRows: 6 }"
        placeholder="Description de ce domaine d'action"
      />
    </el-form-item>
    <el-form-item label="Couleur" prop="color">
      <el-input v-model="form.color" placeholder="Ex: blue-800, green-500" />
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
    <ImageField
      :model="model"
      :model-id="form.id ? form.id : null"
      :min-width="1600"
      :min-height="600"
      :aspect-ratio="8 / 3"
      :field="form.image"
      label="Photo du domaine d'action"
      @add-or-crop="photo = $event"
      @delete="photo = null"
    ></ImageField>
    <div class="mb-6 flex text-xl text-gray-800">Visibilité</div>
    <ItemDescription container-class="mb-6">
      Si vous souhaitez rendre ce domaine d'action visible, cochez la case.
    </ItemDescription>
    <el-form-item prop="published" class="flex-1">
      <el-checkbox v-model="form.published">En ligne</el-checkbox>
    </el-form-item>
    <div class="flex pt-2">
      <el-button type="primary" :loading="loading" @click="onSubmit"
        >Enregistrer</el-button
      >
    </div>
  </el-form>
</template>
<script>
export default {
  props: {
    domaine: {
      type: Object,
      default() {
        return {}
      },
    },
    tags: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      form: {
        ...this.domaine,
      },
      model: 'thematique',
      photo: null,
      rules: {
        name: [
          {
            required: true,
            message: "Veuillez renseigner le nom du domaine d'action",
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.thematiqueForm.validate((valid) => {
        if (valid) {
          this.$api
            .addOrUpdateThematique(this.domaine.id, this.form)
            .then((response) => {
              this.form = response.data
              if (this.photo) {
                this.$api
                  .uploadImage(
                    this.form.id,
                    this.model,
                    this.photo.blob,
                    this.photo.cropSettings
                  )
                  .then(() => {
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
      this.$router.push('/dashboard/contents/domaines')
      this.$message({
        message: "Le domaine d'action a été enregistré !",
        type: 'success',
      })
    },
  },
}
</script>
