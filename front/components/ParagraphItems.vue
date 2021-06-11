<template>
  <div>
    <div
      v-for="(item, index) in values"
      :key="index"
      class="bg-gray-50 py-4 px-4 mb-2"
    >
      {{ index }}
      <ParagraphFieldsItem
        :fields="fields"
        :item="item"
        @update="onUpdate($event, index)"
        @remove="onRemove(index)"
      />
    </div>
    <el-button type="secondary" @click="addRow"> Ajouter une entrée </el-button>
  </div>
</template>

<script>
export default {
  props: {
    fields: {
      type: Array,
      required: true,
    },
    items: {
      type: Array,
      default: null,
    },
  },
  data() {
    return {
      values: this.items ? [...this.items] : [],
    }
  },
  methods: {
    addRow() {
      const newRow = {}
      this.fields.forEach((field) => {
        newRow[field.key] = null
      })
      this.values.push(newRow)
    },
    onRemove(index) {
      console.log('onRemove', index)
      // this.values.findIndex((value) => value == payload)
      // console.log('this values', this.values)
      this.values.splice(index, 1)
      // this.$emit('update-items', this.values)
    },
    onUpdate(payload, index) {
      this.values[index] = payload
      this.$emit('update-items', this.values)
    },
  },
}
</script>
