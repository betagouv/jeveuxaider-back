<template>
  <el-form ref="tagForm" :model="form" label-position="top" :rules="rules">
    <div class="mb-6 text-xl text-gray-800">Informations générales</div>

    <el-form-item label="Tag" prop="name">
      <el-input v-model="form.name" placeholder="Tag" />
    </el-form-item>

    <el-form-item label="Type" prop="type" class="flex-1">
      <el-select
        v-model="form.type"
        clearable
        placeholder="Sélectionner un type"
      >
        <el-option
          v-for="item in $store.getters.taxonomies.tag_types.terms"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        ></el-option>
      </el-select>
    </el-form-item>

    <el-form-item
      v-if="form.type == 'competence'"
      label="Groupe"
      prop="group"
      class="flex-1"
    >
      <el-select
        v-model="form.group"
        clearable
        placeholder="Sélectionner un type"
      >
        <el-option
          v-for="item in $store.getters.taxonomies.tag_groups.terms"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        ></el-option>
      </el-select>
    </el-form-item>

    <el-form-item label="Ordre" prop="order_column">
      <el-input v-model="form.order_column" placeholder="Ordre du tag" />
    </el-form-item>

    <ImageField
      :crop="false"
      accepted-files="image/svg+xml"
      :model="model"
      :model-id="form.id ? form.id : null"
      :field="form.image"
      :max-size="1000000"
      :preview-width="80"
      preview-area-class="bg-primary rounded-md p-3"
      label="Icone"
      label-class="mb-6 text-xl text-gray-800"
      description="Format accepté: SVG"
      @add-or-crop="icone = $event"
      @delete="icone = null"
    ></ImageField>

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
    tag: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      loading: false,
      model: 'tag',
      form: { ...this.tag },
      icone: null,
    }
  },
  computed: {
    rules() {
      const rules = {
        name: [
          {
            required: true,
            message: 'Veuillez renseigner un tag',
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
      }
      if (this.form.type == 'competence') {
        rules.group = [
          {
            required: true,
            message: 'Veuillez renseigner un groupe',
            trigger: 'blur',
          },
        ]
      }
      return rules
    },
  },
  created() {
    if (this.tag.name) {
      this.form.name = this.tag.name.fr
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.tagForm.validate((valid) => {
        if (valid) {
          this.$api
            .addOrUpdateTag(this.tag.id, this.form)
            .then((response) => {
              if (this.icone) {
                this.$api
                  .uploadImage(
                    response.data.id,
                    this.model,
                    this.icone.blob,
                    null
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
      this.$router.push('/dashboard/contents/tags')
      this.$message({
        message: 'Le tag a été enregistré !',
        type: 'success',
      })
    },
  },
}
</script>
