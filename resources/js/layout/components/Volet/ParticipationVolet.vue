<template>
  <Volet>
    <template v-slot:content="{ row }">
      <el-card shadow="hover" class="overflow-visible mt-24">
        <div slot="header" class="clearfix flex flex-col items-center">
          <div class="-mt-10">
            <el-avatar class="bg-primary">{{ row.profile.short_name }}</el-avatar>
          </div>
          <div class="font-bold text-lg mt-3">{{ row.profile.full_name }}</div>
        </div>
        <div class="flex items-center justify-center mb-4">
          <state-tag :state="row.state" size="small" class="flex items-center"></state-tag>
        </div>
        <participation-infos :participation="row"></participation-infos>
      </el-card>
      <template>
        <el-form ref="participationForm" :model="form" label-position="top">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">Statut</div>
          <item-description>Vous pouvez sélectionner le statut de la participation. A noter que des
            notifications emails seront envoyées.</item-description>
          <el-form-item label="Statut" prop="state" class="flex-1">
            <el-select
              v-model="form.state"
              filterable
              clearable
              placeholder="Sélectionner un statut"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.participation_workflow_states.terms"
                :key="item.value"
                :label="`${item.label}`"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>
          <div class="flex pt-2">
            <el-button type="primary" :loading="loading" @click="onSubmit">Enregistrer</el-button>
          </div>
        </el-form>
      </template>
    </template>
  </Volet>
</template>

<script>
import Volet from "@/layout/components/Volet";
import StateTag from "@/components/StateTag.vue";
import { updateParticipation } from "@/api/participation";
import VoletRow from "@/mixins/VoletRow";
import ItemDescription from "@/components/forms/ItemDescription";
import ParticipationInfos from "@/components/infos/ParticipationInfos";

export default {
  name: "ParticipationVolet",
  components: { StateTag, Volet, ItemDescription, ParticipationInfos },
  mixins: [VoletRow],
  data() {
    return {
      loading: false,
      form: {}
    };
  },
  methods: {
    onSubmit() {
      this.$confirm("Êtes vous sur de vos changements ?", "Confirmation", {
        confirmButtonText: "Je confirme",
        cancelButtonText: "Annuler",
        type: "warning"
      }).then(() => {
        this.loading = true;
        updateParticipation(this.form.id, this.form)
          .then(response => {
            this.loading = false;
            this.form = response.data;
            this.$message({
              type: "success",
              message: "La participation a été mise à jour"
            });
            this.$emit("updated", response.data);
          })
          .catch(() => {
            this.loading = false;
          });
      });
    }
  }
};
</script>
