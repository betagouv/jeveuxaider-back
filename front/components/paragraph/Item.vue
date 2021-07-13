<template>
  <div class="flex flex-col space-y-4">
    <el-form
      :ref="`paragraph-fields-item-form`"
      :model="form"
      :rules="rules"
      label-position="top"
      class="flex flex-col space-y-4"
    >
      <el-form-item
        v-for="(field, index) in schema"
        :key="index"
        :label="field.label"
        :prop="field.key"
      >
        <el-input
          v-if="field.type == 'text'"
          v-model="form[field.key]"
          :placeholder="field.placeholder ? field.placeholder : ''"
          @input="onInput"
        />

        <el-input
          v-if="field.type == 'textarea'"
          v-model="form[field.key]"
          type="textarea"
          :placeholder="field.placeholder ? field.placeholder : ''"
          rows="4"
          @input="onInput"
        />

        <RichEditor
          v-if="field.type == 'richtext'"
          v-model="form[field.key]"
          @input="onInput"
        />

        <template v-if="field.type == 'autocomplete'">
          <AutocompleteStructureSingle
            v-if="field.model == 'structure'"
            :selected="item"
            placeholder="Rechercher une association"
            @change="onAutocompleteChange"
          />
        </template>
      </el-form-item>
    </el-form>

    <div class="flex justify-end">
      <el-button type="danger" size="small" @click="onRemove">
        <i class="el-icon-delete" /> Supprimer cette entrée
      </el-button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    schema: {
      type: Array,
      required: true,
    },
    item: {
      type: Object,
      default: null,
    },
    rules: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      form: { ...this.item },
    }
  },
  methods: {
    onRemove() {
      this.$emit('remove', this.form)
    },
    onInput() {
      this.$refs[`paragraph-fields-item-form`].validate((valid) => {
        if (!valid) {
          console.log('NOT VALID FORM ROW')
        }
        this.$emit('update', this.form)
      })
    },
    onAutocompleteChange(payload) {
      this.$emit('update', payload)
    },
  },
}
</script>
