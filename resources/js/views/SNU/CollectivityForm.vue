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

      <el-form-item label="Nom de la collectivité" prop="title">
        <el-input v-model="form.title" placeholder="Nom de la collectivité" />
        <item-description>Accessible à l'adresse : {{baseUrl}}/collectivites/{{ form.title|slugify }}</item-description>
      </el-form-item>

       <el-form-item label="Type" prop="type">
        <el-select v-model="form.type" placeholder="Selectionner le type">
          <el-option
            v-for="item in $store.getters.taxonomies.collectivities_types.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>

       <el-form-item label="Département" prop="department">
            <el-select v-model="form.department" filterable placeholder="Département">
              <el-option
                v-for="item in $store.getters.taxonomies.departments.terms"
                :key="item.value"
                :label="`${item.value} - ${item.label}`"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>

        <!-- <el-form-item label="Liste des codes postaux" prop="zips" class="flex-1">
          <item-description>Séparer les codes postaux par des virgules. Ex: 75001,75002,75003</item-description>
          <el-input
            v-model="form.zips"
            name="zips"
            type="textarea"
            :autosize="{ minRows: 3, maxRows: 10 }"
            placeholder="Codes postaux..."
          ></el-input>
        </el-form-item> -->

      <div v-if="mode == 'edit'" class="mb-6">
        <div class="mb-6 text-xl text-gray-800">Photo de la collectivité</div>
        <item-description>Ca upload le file quand on le choisit. TODO: trouver un component d'upload avec crop ?</item-description>
        <input type="file" @change="selectFile">
      </div>

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">Enregistrer</el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import {
  getCollectivity,
  updateCollectivity,
  addCollectivity,
  addOrUpdateCollectivity,
  uploadImage
} from "@/api/app";
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
      baseUrl: process.env.MIX_API_BASE_URL,
      loading: false,
      form: {
        type: 'department',
        image: ''
      }
    };
  },
  computed: {
    rules() {
      let rules = {
        title: [
          {
            required: true,
            message: "Veuillez renseigner un nom de collectivité",
            trigger: "blur"
          }
        ],
      };

      if(this.form.type == 'department') {
        rules.department = [
          {
            required: true,
            message: "Veuillez choisir un département",
            trigger: "blur"
          }
        ]
      }

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
    selectFile(event) {
        // `files` is always an array because the file input may be in multiple mode
        console.log(event);
        uploadImage(this.id, 'collectivity', event.target.files[0])
        .then((response) => {
          console.log(response)
        })
        .catch(() => {
          this.loading = false;
        });
    },
    onSubmit() {
      this.loading = true;
      this.$refs["collectivityForm"].validate(valid => {
        if (valid) {
          if (this.id) {
            updateCollectivity(this.form.id, this.form)
              .then(() => {
                this.loading = false;
                this.$router.push('/dashboard/contents?type=Collectivités');
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
                this.$router.push('/dashboard/contents?type=Collectivités');
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
