<template>
  <div class="">
    <i
      class="el-icon-close cursor-pointer absolute text-xl"
      style="right: 32px; top: 16px"
      @click="handleCloseVolet"
    ></i>
    <slot name="content" :row="row">TOTO</slot>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "Volet",
  computed: {
    ...mapGetters({
      row: "volet/row"
    })
  },
  methods: {
    handleCloseVolet() {
      this.$store.commit("volet/hide");
    }
  }
};
</script>
