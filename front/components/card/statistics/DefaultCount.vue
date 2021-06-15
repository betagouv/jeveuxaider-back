<template>
  <el-card shadow="never" class="mr-5 mb-5 p-5 uppercase" style="width: 330px">
    <div>
      <div class="label mb-3 text-lg font-bold text-secondary">
        {{ label }}
      </div>
      <div class="count text-primary font-medium text-2xl">
        {{ value | formatNumber }}
      </div>
      <div class="mt-5">
        <slot></slot>
      </div>
    </div>
  </el-card>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: true,
    },
    value: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      data: null,
    }
  },
}
</script>
