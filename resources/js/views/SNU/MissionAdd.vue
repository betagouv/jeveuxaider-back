<template>
  <div v-show="!$store.getters.loading" class="mission-form pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Mission</div>
    <div class="mb-8 font-bold text-2xl text-gray-800">Création d'une nouvelle mission</div>
    <div style="max-width: 600px" v-if="step == 1">
      <div class="mb-6 text-xl text-gray-800 flex items-center">
        <div> À partir d'un modèle</div>
        <el-tag type="success" size="small" class="ml-2">
          Publication automique
        </el-tag>
      </div>
      <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        <div class="flex-1">
          En choisissant un modèle, votre mission sera directement en ligne dès sa publication. <br />Le modèle inclue le choix du titre, de la thématique, de la description et de l’objectif de la mission.
        </div>
      </div>
      <!-- // TODO : Récupérer les thématiques prioritaires au change du select -->
      <el-select v-model="template_id" placeholder="Réserve Thématique" class="mb-8">
        <el-option
          v-for="thematique in templates"
          :key="thematique.id"
          :label="thematique.title"
          :value="thematique.id">
        </el-option>
      </el-select>
      <div v-for="template in templates" :key="template.label" class="bg-gray-100 p-4 mb-4 rounded flex items-center">
        <div class="mr-3">
          <div class="mb-1">{{ template.title }}</div>
          <div class="text-xs text-gray-400">{{ template.subtitle }}</div>
        </div>
        <el-button plain type="primary" size="medium" class="ml-auto h-full" @click="handleSelectTemplate(template)">Choisir</el-button>
      </div>
      <div class="mt-10 text-xl text-gray-800 flex items-center">
        <div>À partir d'un modèle libre</div>
        <el-tag type="warning" size="small" class="ml-2">
          Validation par un référent
        </el-tag>
      </div>
      <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        <div class="flex-1">
          Vous écrivez le contenu de votre mission. Celle-ci sera soumise à validation du référent départemenntal avant d’être publiée.
        </div>
      </div>
      <div class="bg-gray-100 p-4 mb-4 rounded flex items-center">
        <div class="mr-3">
          <div class="mb-1">Modèle libre</div>
          <div class="text-xs text-gray-400">Je personnalise le contenu de ma mission.</div>
        </div>
        <el-button plain type="primary" class="ml-auto h-full" @click="handleSelectTemplate()">Choisir</el-button>
      </div>
    </div>
    <MissionForm :mission="mission" v-else />
  </div>
</template>

<script>
import { fetchMissionTemplates } from "@/api/app";
import { getStructure } from "@/api/structure";
import MissionForm from "@/views/SNU/MissionForm";

export default {
  name: "MissionAdd",
  components: {
    MissionForm,
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
    if(this.step == 1) {
      fetchMissionTemplates().then(res => {
        this.templates = res.data.data;
      });
    }
  },
  data() {
    return {
      step: this.$router.history.current.query.step || 1,
      template_id: null,
      templates: []
    };
  },
  methods: {
    async handleSelectTemplate(template) {
      const { data } = await getStructure(this.structureId)
      this.$router.push({
        name: 'MissionFormAdd',
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
        })
    }
  }
};
</script>
