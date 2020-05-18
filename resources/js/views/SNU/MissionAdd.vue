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
          En choisissant un modèle, votre mission sera automatiquement en ligne dès sa publication. <br />Le modèle inclut le choix de la Réserve thématique, le titre, la description et l’objectif de la mission.
        </div>
      </div>
      <el-select v-model="thematique" placeholder="Réserve Thématique" class="mb-8">
        <el-option
          v-for="item in thematiques"
          :key="item.value"
          :label="item.label"
          :value="item.value">
        </el-option>
      </el-select>
      <div v-for="item in missions_models" :key="item.label" class="bg-gray-100 p-4 mb-4 rounded flex items-center">
        <div class="mr-3">
          <div class="mb-1">{{ item.label }}</div>
          <div class="text-xs text-gray-700">{{ item.description }}</div>
        </div>
        <el-button plain type="primary" size="medium" class="ml-auto h-full" @click="step = 2">Choisir</el-button>
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
          <div class="text-xs text-gray-700">Je personnalise le contenu de ma mission.</div>
        </div>
        <el-button plain type="primary" class="ml-auto h-full">Choisir</el-button>
      </div>
    </div>
    <MissionForm :structure-id="structureId" v-else />
  </div>
</template>

<script>
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
    }
  },
  data() {
    return {
      step: 1,
      loading: false,
      thematiques: [{
          value: 'Crise sanitaire Covid-19',
          label: 'Crise sanitaire Covid-19'
        }, {
          value: 'sante',
          label: 'Santé'
        }, {
          value: 'environnement',
          label: 'Environnement'
        }, {
          value: 'education',
          label: 'Éducation'
        }],
      thematique: '',
      missions_models: [{
          label: 'Aide alimentaire et d’urgence',
          description: 'Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis',
        }, {
          label: 'Garde exceptionnelle d’enfants',
          description: 'J’aide à garder des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance',
        }, {
          label: 'Lien avec les personnes fragiles isolées',
          description: 'Je participe à maintenir le lien (téléphone, visio, mail...) avec des personnes fragiles isolées : personnes âgées, malades ou en situation de handicap.',
        }, {
          label: 'Solidarité de proximité',
          description: 'Je fais les courses de produits essentiels pour mes voisins les plus fragiles.',
        }, {
          label: 'Soutien scolaire à distance',
          description: 'J’aide à distance les élèves à faire leurs devoirs.',
        }]
    };
  }
};
</script>
