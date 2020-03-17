<template>
  <div id="app" class="h-full font-sans">
    <transition name="fade" mode="out-in">
        <router-view />
    </transition>
  </div>
</template>

<script>
// import { mapGetters } from "vuex";

export default {
  data() {
    return {
      message: null
    };
  },
  computed: {
    // ...mapGetters(["profile", "isImpersonating"])
  },
  created() {
      /*
    this.$store.subscribeAction({
      after: action => {
        if (action.type == "user/get") {
          if (this.isImpersonating && !this.message) {
            this.message = this.$message({
              showClose: false,
              duration: 0,
              message: `Attention ! Vous vous faites passer pour ${this.profile.full_name}.`,
              type: "warning"
            });
          }
        } else if (!this.isImpersonating && this.message) {
          this.message.close();
          this.message = null;
        }
      }
    });
    */
  }
};
</script>
