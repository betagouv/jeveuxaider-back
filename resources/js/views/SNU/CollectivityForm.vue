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
        <item-description>Résolution minimale: {{ imgMinWidth }} par {{ imgMinHeight }} pixels</item-description>
        <input type="file" accept="image/*" @change="selectFile">

        <div v-show="imgSrc" class="mt-4">
          <div class="p-2 bg-black">
            <vue-cropper
              ref="cropper"
              :src="imgSrc"
              :aspectRatio="128/85"
              :zoomable="false"
              :movable="false"
              :zoomOnTouch="false"
              :zoomOnWheel="false"
              :autoCropArea="1"
              preview=".preview"
              @ready="cropImage"
              @cropmove="ensureMinWidth"
            >
            </vue-cropper>
          </div>

          <div class="actions mt-4">
            <el-button type="secondary" @click.prevent="cropImage">Recadrer</el-button>
            <el-button type="secondary" @click.prevent="reset">Réinitialiser</el-button>
          </div>

          <div class="preview-area">
            <div class="mt-4">Apercu :</div>
            <img
              v-if="cropImgSrc"
              :src="cropImgSrc"
              alt="Cropped Image"
            />
            <div v-else class="crop-placeholder" />
          </div>
        </div>


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
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';

export default {
  name: "CollectivityForm",
  components: { ItemDescription, VueCropper },
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
      img: null,
      imgSrc: '',
      imgMinWidth: 1024,
      imgMinHeight: 680,
      cropImg: null,
      cropImgSrc: '',
      form: {
        type: 'department',
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
      if (!event.target.files[0]) {
        return;
      }
      if (event.target.files[0].type.indexOf('image/') === -1) {
        this.$message({
          message: "Veuillez sélectionner une image",
          type: "error"
        });
        return;
      }
      if (typeof FileReader === 'function') {
        const reader = new FileReader();
        reader.onload = (readerEvent) => {
          console.log(readerEvent)
          // Validate the File Height and Width.
          let image = new Image();
          image.src = readerEvent.target.result;
          image.onload = (imageEvent) => {
            var height = imageEvent.path[0].height;
            var width = imageEvent.path[0].width;
            if (height < this.imgMinHeight || width < this.imgMinWidth) {
              this.$message({
                message: `Résolution minimale: ${this.imgMinWidth} par ${this.imgMinHeight} pixels`,
                type: "error"
              });
            }
            else {
              this.img = event.target.files[0];
              this.imgSrc = readerEvent.target.result;
              this.$refs.cropper.replace(this.imgSrc);
            }
          };
        };
        reader.readAsDataURL(event.target.files[0]);
      }
      else {
        console.log("FileReader API not supported")
      }
    },
    cropImage() {
      this.cropImgSrc = this.$refs.cropper.getCroppedCanvas().toDataURL();
      fetch(this.cropImgSrc)
        .then(res => res.blob())
        .then(blob => this.cropImg = blob)
    },
    reset() {
      this.$refs.cropper.reset();
      this.cropImage()
    },
    ensureMinWidth(event) {
      let data = this.$refs.cropper.getData();
      if (data.width < this.imgMinWidth || data.height < this.imgMinHeight) {
        event.preventDefault();
        if (data.width < this.imgMinWidth) {
          data.width = this.imgMinWidth;
        }
        if (data.height < this.imgMinHeight) {
          data.height = this.imgMinHeight;
        }
        this.$refs.cropper.setData(data);
      }
    },
    onSubmit() {
      this.loading = true;
      this.$refs["collectivityForm"].validate(valid => {
        if (valid) {
          addOrUpdateCollectivity(this.id, this.form)
            .then((response) => {
              this.form = response.data;
              if(this.img || this.cropImg) {
                let finalImg = this.cropImg ? this.cropImg : this.img
                uploadImage(this.form.id, 'collectivity', finalImg)
              }

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
          this.loading = false;
        }
      });
    }
  }
};
</script>

<style lang="sass" scoped>
.preview-area
  width: 307px

.preview
  width: 100%
  height: calc(372px * (85 / 128))
  overflow: hidden

.crop-placeholder
  width: 100%
  height: 200px
  background: #ccc

.cropped-image img
  max-width: 100%
</style>
