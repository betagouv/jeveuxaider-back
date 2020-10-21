<template>
  <div
    v-if="!$store.getters.loading"
    class="profile-form max-w-2xl pl-12 pb-12"
  >
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Tag</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">{{ form.name.fr }}</div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">Nouveau tag</div>

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
  </div>
</template>

<script>
import { getTag, addOrUpdateTag, uploadImage } from '@/api/app'
import ImageField from '@/components/forms/ImageField.vue'

export default {
  name: 'TagForm',
  components: { ImageField },
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
      loading: false,
      model: 'tag',
      form: {},
      icone: null,
    }
  },
  computed: {
    rules() {
      let rules = {
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
    if (this.mode == 'edit') {
      this.$store.commit('setLoading', true)
      getTag(this.id)
        .then((response) => {
          this.$store.commit('setLoading', false)
          this.form = response.data
          this.form.name = response.data.name.fr
        })
        .catch(() => {
          this.loading = false
        })
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['tagForm'].validate((valid) => {
        if (valid) {
          addOrUpdateTag(this.id, this.form)
            .then((response) => {
              if (this.icone) {
                uploadImage(
                  response.data.id,
                  this.model,
                  this.icone.blob,
                  null
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
      this.$router.push('/dashboard/contents/tags')
      this.$message({
        message: 'Le tag a été enregistré !',
        type: 'success',
      })
    },
  },
}
</script>
