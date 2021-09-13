<template>
  <div>
    <div v-if="items && items.length">
      <div
        v-for="(item, index) in items"
        :key="item.uuid || item.id"
        class="bg-gray-50 py-4 px-4 mb-2"
      >
        <ParagraphItem
          :schema="schema"
          :rules="rules"
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
      <el-button type="secondary" @click="onAdd">
        Ajouter une entrée
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
    rules: {
      type: Object,
      default() {
        return {}
      },
    },
    items: {
      type: Array,
      default: () => {
        return []
      },
    },
  },
  methods: {
    onAdd() {
      const item = {}
      this.schema.forEach((field) => {
        item[field.key] = null
      })
      this.$emit('add', item)
    },
    onUpdate(payload, index) {
      this.$emit('update', { payload, index })
    },
    onRemove(index) {
      this.$emit('remove', index)
    },
  },
}
</script>
