<template>
  <div class="young flex cursor-pointer" @click="handleClick">
    <el-avatar class="bg-primary w-10 rounded-full">
      {{ young.first_name[0] }}{{ young.last_name[0] }}
    </el-avatar>
    <div class="flex flex-col ml-6" style="min-width: 250px;">
      <div class="text-gray-800">
        {{ young.first_name }} {{ young.last_name }}
      </div>
      <div class="uppercase text-xs text-secondary">
        {{ young.email }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'YoungTeaser',
  props: {
    young: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {}
  },
  methods: {
    handleClick() {
      this.$router.push(`/young/${this.young.id}`)
    },
  },
}
</script>
