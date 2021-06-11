<template>
  <div>
    <div v-if="values.length > 0">
      <div
        v-for="(item, index) in values"
        :key="item.uuid"
        class="bg-gray-50 py-4 px-4 mb-2"
      >
        <ParagraphRowItems
          :fields="fields"
          :item="item"
          @update="onUpdate($event, index)"
          @remove="onRemove(index)"
        />
      </div>
    </div>
    <div v-else class="bg-gray-50 py-4 px-4 mb-2 text-gray-500">
      Aucune entrée
    </div>
    <div class="flex justify-end">
      <el-button type="secondary" @click="addRow">
        Ajouter une entrée
      </el-button>
    </div>
  </div>
</template>

<script>
import { uniqueId } from 'lodash'

export default {
  props: {
    fields: {
      type: Array,
      required: true,
    },
    items: {
      type: Array,
      default: () => {
        return []
      },
    },
  },
  data() {
    return {
      values: [...this.items],
    }
  },
  methods: {
    addRow() {
      const newRow = {}
      this.fields.forEach((field) => {
        newRow[field.key] = null
      })

      this.values.push({
        ...newRow,
        uuid: uniqueId(),
      })
    },
    onRemove(index) {
      this.$delete(this.values, index)
      this.$emit('update-items', this.values)
    },
    onUpdate(payload, index) {
      this.$set(this.values, index, payload)
      this.$emit('update-items', this.values)
    },
  },
}
</script>
