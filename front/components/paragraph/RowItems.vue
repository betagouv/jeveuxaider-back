<template>
  <div class="flex flex-col space-y-4">
    <el-form
      :ref="`paragraph-fields-item-form`"
      :model="form"
      label-position="top"
      :rules="rules"
      class="flex flex-col space-y-4"
    >
      <el-form-item
        v-for="(field, index) in fields"
        :key="index"
        :label="field.label"
        :prop="field.key"
      >
        <template v-if="field.type == 'text'">
          <el-input
            v-model="form[field.key]"
            :placeholder="field.placeholder ? field.placeholder : ''"
            @input="onSubmit"
          />
        </template>
        <template v-if="field.type == 'textarea'">
          <el-input
            v-model="form[field.key]"
            type="textarea"
            :placeholder="field.placeholder ? field.placeholder : ''"
            rows="4"
            @input="onSubmit"
          />
        </template>
        <template v-if="field.type == 'richtext'">
          <RichEditor v-model="form[field.key]" @input="onSubmit" />
        </template>
      </el-form-item>
    </el-form>
    <div class="flex justify-end">
      <el-button type="danger" size="small" @click="onRemove"
        ><i class="el-icon-delete" /> Supprimer cette entrée</el-button
      >
    </div>
  </div>
</template>

<script>
export default {
  props: {
    fields: {
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
    onSubmit() {
      this.$refs[`paragraph-fields-item-form`].validate((valid) => {
        if (valid) {
          this.$emit('update', this.form)
        } else {
          console.log('NOT VALID FORM ROW')
        }
      })
    },
  },
}
</script>
