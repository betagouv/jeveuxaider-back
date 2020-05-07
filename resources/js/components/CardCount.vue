<template>
  <div
    class="stat-count w-full rounded-lg mr-6 mb-6 p-6 uppercase flex items-center border border-gray-300"
    :class="{ 'hover:border-blue-900 cursor-pointer': link }"
    @click="onClick"
    style="max-width: 240px"
  >
    <div>
      <div class="label mb-3 text-lg font-bold text-secondary">
        {{ label }}
      </div>
      <template v-if="data">
        <div class="count text-primary font-medium text-2xl">
          {{ data.total|formatNumber }}
        </div>
      </template>
      <template v-else>
        <i class="el-icon-loading"></i>
      </template>
    </div>
    <i v-if="link" class="ml-auto el-icon-arrow-right text-secondary"></i>
  </div>
</template>

<script>
import { statistics } from "../api/app";
export default {
  name: "StatCount",
  props: {
    label: {
      type: String,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    link: {
      type: String,
      required: false,
      default: null
    }
  },
  data() {
    return {
      data: null
    };
  },
  created() {
    statistics(this.name, { type: 'light' }).then(response => {
      this.data = response.data;
    });
  },
  methods: {
    onClick() {
      if (this.link) {
        this.$router.push(this.link);
      }
    }
  }
};
</script>
