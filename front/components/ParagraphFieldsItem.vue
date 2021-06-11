<template>
  <div class="flex items-center space-x-4">
    <el-form
      :ref="`paragraph-fields-item-form`"
      :model="form"
      label-position="top"
      :rules="rules"
      class="flex space-x-4 flex-1"
    >
      <el-form-item
        v-for="(field, index) in fields"
        :key="index"
        :label="field.label"
        :prop="field.key"
        class="w-full"
      >
        <template v-if="field.type == 'text'">
          <el-input
            v-model="form[field.key]"
            placeholder="Placeholder text"
            @input="onSubmit"
          />
        </template>
        <template v-if="field.type == 'textarea'">
          <el-input
            v-model="form[field.key]"
            type="textarea"
            placeholder="Placeholder textarea"
            @input="onSubmit"
          />
        </template>
      </el-form-item>
    </el-form>
    <div class="">
      <el-button type="danger" @click="onRemove"
        ><i class="el-icon-delete"
      /></el-button>
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
