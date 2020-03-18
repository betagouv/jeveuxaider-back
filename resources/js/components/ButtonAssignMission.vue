<template>
  <el-button
    :type="type"
    :size="size"
    :loading="loading"
    :disabled="registrationsLeft == 0"
    @click.stop="onSubmit"
    >Proposer cette mission</el-button
  >
</template>

<script>
import { updateYoung } from "@/api/young";

export default {
  props: {
    young: {
      type: Object,
      required: true
    },
    mission: {
      type: Object,
      required: true
    },
    size: {
      type: String,
      default: "mini"
    },
    type: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      loading: false
    };
  },
  computed: {
    registrationsLeft() {
      let places = this.mission.participations_max - this.mission.youngs_count;
      return places > 0 ? places : 0;
    }
  },
  methods: {
    onSubmit() {
      this.$confirm(
        "Vous êtes sur le point d'assigner la mission à " +
          this.young.full_name,
        "Confirmation",
        {
          confirmButtonText: "Je confirme",
          cancelButtonText: "Annuler",
          type: "warning"
        }
      )
        .then(() => {
          this.loading = true;
          updateYoung(this.young.id, {
            ...this.young,
            mission_id: this.mission.id,
            state: "Mission proposée"
          })
            .then(() => {
              this.loading = false;
              this.$message({
                type: "success",
                message: "La mission a été assignée à " + this.young.full_name
              });
              this.$router.push(`/young/${this.young.id}`);
            })
            .catch(error => {
              this.loading = false;
              this.errors = error.response.data.errors;
            });
        })
        .catch(() => {});
    }
  }
};
</script>
