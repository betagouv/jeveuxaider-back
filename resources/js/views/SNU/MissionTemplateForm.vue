<template>
  <div v-if="!$store.getters.loading" class="profile-form max-w-2xl pl-12 pb-12">
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Page</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">{{ form.title }}</div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">Nouveau modèle</div>

    <el-form ref="missionTemplateForm" :model="form" label-position="top" :rules="rules">
      <div class="mb-6 text-xl text-gray-800">Informations générales</div>

      <el-form-item label="Titre" prop="title">
        <el-input v-model="form.title" placeholder="Titre" />
      </el-form-item>

      <el-form-item label="Thématique" prop="thematique" class="flex-1">
        <el-select
          v-model="form.thematique_id"
          clearable
          placeholder="Sélectionner une thématique"
        >
          <el-option
            v-for="item in $store.getters.thematiques"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          ></el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="Objectif" prop="objectif">
        <ckeditor :editor="editor" v-model="form.objectif" :config="editorConfig"></ckeditor>
      </el-form-item>

      <el-form-item label="Description" prop="description">
        <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>
      </el-form-item>

      <div class="mb-6 mt-12 flex text-xl text-gray-800">Visibilité</div>
      <item-description>Si vous souhaitez rendre ce modèle visible, cochez la case.</item-description>
      <el-form-item prop="published" class="flex-1">
        <el-checkbox v-model="form.published">En ligne</el-checkbox>
      </el-form-item>

      <div class="mb-6 mt-12 flex text-xl text-gray-800">Mission prioritaire</div>
      <item-description>Les modèles de missions prioritaires sont mises en avant lors de la création d'une nouvelle mission.</item-description>
      <el-form-item prop="priority" class="flex-1">
        <el-checkbox v-model="form.priority">Mission prioritaire</el-checkbox>
      </el-form-item>

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">Enregistrer</el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { getMissionTemplate, addOrUpdateMissionTemplate } from "@/api/app";
import ItemDescription from "@/components/forms/ItemDescription";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
  name: "MissionTemplateForm",
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
      form: {
        published: true,
        priority: false
      },
      editor: ClassicEditor,
      editorConfig: {
        toolbar: [
          "heading",
          "bold",
          "italic",
          "|",
          "link",
          "bulletedList",
          "numberedList"
        ],
        heading: {
          options: [
            {
              model: "paragraph",
              title: "Paragraph",
              class: "ck-heading_paragraph"
            },
            {
              model: "heading2",
              view: "h2",
              title: "Heading 2",
              class: "ck-heading_heading2"
            },
            {
              model: "heading3",
              view: "h3",
              title: "Heading 3",
              class: "ck-heading_heading3"
            }
          ]
        }
      }
    };
  },
  computed: {
    rules() {
      let rules = {
        title: [
          {
            required: true,
            message: "Veuillez renseigner un titre",
            trigger: "blur"
          }
        ],
        description: [
          {
            required: true,
            message: "Veuillez renseigner une description",
            trigger: "blur"
          }
        ],
        objectif: [
          {
            required: true,
            message: "Veuillez renseigner un objectif",
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
      getMissionTemplate(this.id)
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
      this.$refs["missionTemplateForm"].validate(valid => {
        if (valid) {
          addOrUpdateMissionTemplate(this.id, this.form)
            .then(() => {
              this.loading = false;
              this.$router.push("/dashboard/contents/mission-templates");
              this.$message({
                message: "Le modèle a été enregistré !",
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
