<template>
  <div v-if="!$store.getters.loading" class="profile-form max-w-2xl pl-12 pb-12">
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Collectivité</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">{{ form.name }}</div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">Nouvelle collectivité</div>

    <el-form ref="collectivityForm" :model="form" label-position="top" :rules="rules">
      <div class="mb-6 text-xl text-gray-800">Informations générales</div>

      <el-form-item label="Collectivité" prop="name">
        <el-input v-model="form.name" placeholder="Nom de la collectivité" />
      </el-form-item>

      <el-form-item label="Description" prop="description" class="flex-1">
        <el-input
          v-model="form.description"
          name="description"
          type="textarea"
          :autosize="{ minRows: 6, maxRows: 20 }"
          placeholder="Rédigez la réponse"
        ></el-input>
      </el-form-item>

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">Enregistrer</el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { getCollectivity, updateCollectivity, addCollectivity } from "@/api/collectivity";
import ItemDescription from "@/components/forms/ItemDescription";

export default {
  name: "CollectivityForm",
  components: { ItemDescription },
  props: {
    mode: {
      type: String,
      required: true
    },
    id: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      loading: false,
      form: {}
    };
  },
  computed: {
    rules() {
      let rules = {
        name: [
          {
            required: true,
            message: "Veuillez renseigner un nom de collectivité",
            trigger: "blur"
          }
        ],
        description: [
          {
            required: true,
            message: "Veuillez renseigner un nom",
            trigger: "blur"
          }
        ]
      };
      return rules;
    }
  },
  created() {
    if (this.mode == "edit") {
      this.$store.commit("setLoading", true);
      getCollectivity(this.id)
        .then(response => {
          this.$store.commit("setLoading", false);
          this.form = response.data;
        })
        .catch(() => {
          this.loading = false;
        });
    }
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["collectivityForm"].validate(valid => {
        if (valid) {
          if (this.id) {
            updateCollectivity(this.form.id, this.form)
              .then(() => {
                this.loading = false;
                this.$router.go(-1);
                this.$message({
                  message: "La collectivité a été enregistrée !",
                  type: "success"
                });
              })
              .catch(() => {
                this.loading = false;
              });
          } else {
            addCollectivity(this.form)
              .then(() => {
                this.loading = false;
                this.$router.go(-1);
                this.$message({
                  message: "La collectivité a été enregistrée !",
                  type: "success"
                });
              })
              .catch(() => {
                this.loading = false;
              });
          }
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>

<style lang="sass" scoped>

</style>
