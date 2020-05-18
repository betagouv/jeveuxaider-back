<template>
  <div>
    <div class="mb-6 mt-12 flex text-xl text-gray-800">
      À partir d'un modèle
      <el-tag type="success" class="ml-3">Publication automatique</el-tag>
    </div>
    <item-description>En choississant un modèle, votre mission sera directement en ligne dès sa publication. Le modèle inclue le choix du titre, de la thématique, de la description et de l'objectif de la mission.</item-description>
    <div v-if="templates">
      <div v-for="template in templates" :key="template.id" class="bg-gray-100 flex p-4 items-center">
        <div class="flex-1">
          <div class="text-md">{{ template.title }}</div>
          <div class="text-sm text-gray-400">{{ template.subtitle }}</div>
        </div>
        <div class="ml-3">
          <el-button @click="handleClick(template)" type="primary">Choisir</el-button>
        </div>
      </div>
    </div>
    <div class="mb-6 mt-12 flex text-xl text-gray-800">
      À partir d'un modèle libre
      <el-tag type="warning" class="ml-3">Validation par un référent</el-tag>
    </div>
    <item-description>Vous écrivez le contenu de votre mission. Celle-ci sera soumise à validation de votre référent départemental avant d'être publiée.</item-description>
     <div v-for="template in templates" :key="template.id" class="bg-gray-100 flex p-4 items-center">
        <div class="flex-1">
          <div class="text-md">Modèle libre</div>
          <div class="text-sm text-gray-400">Je personnalise le contenu de ma mission.</div>
        </div>
        <div class="ml-3">
          <el-button @click="handleClick" type="primary">Choisir</el-button>
        </div>
      </div>
  </div>
</template>

<script>
import { fetchMissionTemplates, deleteMissionTemplate } from "@/api/app";
import ItemDescription from "@/components/forms/ItemDescription";

export default {
  name: "MissionTemplateForm",
  components: { ItemDescription },
  data() {
    return {
      templates: {}
    };
  },
  created() {
    fetchMissionTemplates().then(res => {
      this.templates = res.data.data;
    });
  },
  methods:{
    handleClick(template){
      this.$emit('selected', template)
    }
  }
};
</script>
