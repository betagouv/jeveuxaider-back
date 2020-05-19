<template>
  <div v-if="!$store.getters.loading" class="profile-form max-w-2xl pl-12 pb-12">
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Tag</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">{{ form.name }}</div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">Nouveau tag</div>

    <el-form ref="tagForm" :model="form" label-position="top" :rules="rules">
      <div class="mb-6 text-xl text-gray-800">Informations générales</div>

      <el-form-item label="Tag" prop="name">
        <el-input v-model="form.name" placeholder="Tag" />
      </el-form-item>

      <el-form-item label="Type" prop="type" class="flex-1">
        <el-select v-model="form.type" clearable placeholder="Sélectionner un type">
          <el-option
            v-for="item in $store.getters.taxonomies.tag_types.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">Enregistrer</el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { getTag, addOrUpdateTag } from "@/api/app";
import ItemDescription from "@/components/forms/ItemDescription";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
  name: "TagForm",
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
      form: {},
    };
  },
  computed: {
    rules() {
      let rules = {
        name: [
          {
            required: true,
            message: "Veuillez renseigner un tag",
            trigger: "blur"
          }
        ],
        type: [
          {
            required: true,
            message: "Veuillez renseigner un type",
            trigger: "blur"
          }
        ],
      };
      return rules;
    }
  },
  created() {
    if (this.mode == "edit") {
      this.$store.commit("setLoading", true);
      getTag(this.id)
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
      this.$refs["tagForm"].validate(valid => {
        if (valid) {
          addOrUpdateTag(this.id, this.form)
            .then(() => {
              this.loading = false;
              this.$router.push("/dashboard/contents/tags");
              this.$message({
                message: "Le tag a été enregistré !",
                type: "success"
              });
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>
