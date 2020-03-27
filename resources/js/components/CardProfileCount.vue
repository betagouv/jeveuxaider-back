<template>
  <div
    class="stat-count w-full rounded-lg mb-6 p-6 uppercase flex items-center shadow"
    :class="{ 'hover:shadow-md cursor-pointer': link }"
    @click="onClick"
  >
    <div>
      <div class="label mb-3 text-lg font-bold text-secondary">
        {{ label }}
      </div>
      <template v-if="data">
        <div v-if="data" class="count text-primary font-medium text-2xl">
          {{ data.total|formatNumber }}
        </div>
        <div v-if="data" class="flex flex-wrap">
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Volontaires</div>
            <div class="">{{ data.volontaire|formatNumber }}</div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Responsable</div>
            <div class="">{{ data.responsable|formatNumber }}</div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Référent</div>
            <div class="">{{ data.referent|formatNumber }}</div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Superviseur</div>
            <div class="">{{ data.superviseur|formatNumber }}</div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Modérateurs</div>
            <div class="">{{ data.admin|formatNumber }}</div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Invités</div>
            <div class="">{{ data.invited|formatNumber }}</div>
          </div>
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
    statistics(this.name).then(response => {
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
