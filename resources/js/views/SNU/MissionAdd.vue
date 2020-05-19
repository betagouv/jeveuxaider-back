<template>
  <div v-show="!$store.getters.loading" class="mission-form pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Mission</div>
    <div class="mb-8 font-bold text-2xl text-gray-800">Création d'une nouvelle mission</div>
    <div style="max-width: 600px" v-if="step == 1">
      <div class="mb-6 text-xl text-gray-800 flex items-center">
        <div>À partir d'un modèle</div>
        <el-tag type="success" size="small" class="ml-2">Publication automique</el-tag>
      </div>
      <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        <div class="flex-1">
          En choisissant un modèle, votre mission sera directement en ligne dès sa publication.
          <br />Le modèle inclue le choix du titre, de la thématique, de la description et de l’objectif de la mission.
        </div>
      </div>
      <el-select
        v-model="domaine_id"
        placeholder="Domaine d'action"
        class="mb-8"
        @change="handleChangeDomaine"
      >
        <el-option
          v-for="domaine in domaines"
          :key="domaine.id"
          :label="domaine.name.fr"
          :value="domaine.id"
        ></el-option>
      </el-select>
      <template v-if="templates.length > 0">
        <div
          v-for="template in templates"
          :key="template.label"
          class="bg-gray-100 p-4 mb-4 rounded flex items-center"
        >
          <div class="mr-3">
            <div class="mb-1">{{ template.title }}</div>
            <div class="text-xs text-gray-400">{{ template.subtitle }}</div>
          </div>
          <el-button
            plain
            type="primary"
            size="medium"
            class="ml-auto h-full"
            @click="handleSelectTemplate(template)"
          >Choisir</el-button>
        </div>
      </template>
      <template v-else>
        <div class="bg-gray-100 p-4 mb-4 rounded flex items-center text-sm">
          <div class="mr-3">
            <div class="mb-1">Des modèles de missions sont à venir</div>
            <div class="text-xs text-gray-400">Revenez dans quelque temps pour les découvrir...</div>
          </div>
        </div>
      </template>
      <div class="mt-10 text-xl text-gray-800 flex items-center">
        <div>À partir d'un modèle libre</div>
        <el-tag type="warning" size="small" class="ml-2">Validation par un référent</el-tag>
      </div>
      <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        <div
          class="flex-1"
        >Vous écrivez le contenu de votre mission. Celle-ci sera soumise à validation du référent départemenntal avant d’être publiée.</div>
      </div>
      <div class="bg-gray-100 p-4 mb-4 rounded flex items-center">
        <div class="mr-3">
          <div class="mb-1">Modèle libre</div>
          <div class="text-xs text-gray-400">Je personnalise le contenu de ma mission.</div>
        </div>
        <el-button
          plain
          type="primary"
          class="ml-auto h-full"
          @click="handleSelectTemplate()"
        >Choisir</el-button>
      </div>
    </div>
    <MissionForm :mission="mission" v-else />
  </div>
</template>

<script>
import { fetchMissionTemplates, fetchTags } from "@/api/app";
import { getStructure } from "@/api/structure";
import MissionForm from "@/views/SNU/MissionForm";

export default {
  name: "MissionAdd",
  components: {
    MissionForm
  },
  props: {
    structureId: {
      type: Number,
      default: null
    },
    mission: {
      type: Object,
      default: null
    }
  },
  created() {
    if (this.step == 1) {
      fetchTags({ "filter[type]": "domaine" }).then(res => {
        this.domaine_id = res.data.data[0] ? res.data.data[0].id : null;
        this.domaines = res.data.data;
        fetchMissionTemplates({ "filter[domaine.id]": this.domaine_id }).then(
          res => {
            this.templates = res.data.data;
          }
        );
      });
    }
  },
  data() {
    return {
      step: this.$router.history.current.query.step || 1,
      template_id: null,
      templates: [],
      domaine_id: null,
      domaines: []
    };
  },
  methods: {
    handleChangeDomaine(domaine_id) {
      fetchMissionTemplates({ "filter[domaine.id]": domaine_id }).then(res => {
        this.templates = res.data.data;
      });
    },
    async handleSelectTemplate(template) {
      const { data } = await getStructure(this.structureId);
      this.$router.push({
        name: "MissionFormAdd",
        params: {
          mission: {
            template_id: template ? template.id : null,
            template: template || null,
            tuteur_id: this.$store.getters.user.profile.id,
            structure_id: this.structureId,
            structure: data
          }
        },
        query: { step: 2 }
      });
    }
  }
};
</script>
