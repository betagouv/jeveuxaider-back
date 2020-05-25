<template>
  <div v-show="!$store.getters.loading" class="mission-form pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Mission</div>
    <div class="mb-8 font-bold text-2xl text-gray-800">
      Création d'une nouvelle mission
    </div>
    <!-- STEP 1 : Choix du domaine d'action -->
    <div v-if="step == 1" class="max-w-3xl">
      <div class="mb-6 text-xl text-gray-800 flex items-center">
        <div>Choisissez le domaine d'action principal</div>
      </div>
      <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        <div class="flex-1">
          Texte pour sensibiliser aux Réserves Thématiques ?
        </div>
      </div>
      <div class="flex flex-wrap">
        <div
          v-for="domaine in domaines"
          :key="domaine.id"
          class="flex flex-col bg-gray-100 p-6 m-2 text-center items-center rounded-md w-56"
        >
          <div
            class="p-2 flex items-center justify-center bg-blue-900 h-14 w-14 rounded-md text-white mb-4"
          >
            <img v-if="domaine.image" :src="domaine.image" />
          </div>
          <div class="mb-4">{{ domaine.name.fr }}</div>
          <el-button
            plain
            type="primary"
            size="medium"
            class="mt-auto"
            @click="hangeSelectDomaine(domaine.id)"
          >
            Choisir
          </el-button>
        </div>
      </div>
    </div>

    <!-- STEP 2 : Choix du modèle de mission -->
    <div v-else-if="step == 2" style="max-width: 600px;">
      <div class="mb-6 text-xl text-gray-800 flex items-center">
        <div>À partir d'un modèle</div>
        <el-tag type="success" size="small" class="ml-2"
          >Publication automique</el-tag
        >
      </div>
      <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        <div class="flex-1">
          En choisissant un modèle, votre mission sera directement en ligne dès
          sa publication.
          <br />Le modèle inclue le choix du titre, de la thématique, de la
          description et de l’objectif de la mission.
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
            >Choisir</el-button
          >
        </div>
      </template>
      <template v-else>
        <div class="bg-gray-100 p-4 mb-4 rounded flex items-center">
          <div class="mr-3">
            <div class="mb-1">Des modèles de missions sont à venir</div>
            <div class="text-xs text-gray-400">
              Revenez dans quelque temps pour les découvrir...
            </div>
          </div>
        </div>
      </template>
      <div class="mt-10 text-xl text-gray-800 flex items-center">
        <div>À partir d'un modèle libre</div>
        <el-tag type="warning" size="small" class="ml-2"
          >Validation par un référent</el-tag
        >
      </div>
      <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        <div class="flex-1">
          Vous écrivez le contenu de votre mission. Celle-ci sera soumise à
          validation du référent départemenntal avant d’être publiée.
        </div>
      </div>
      <div class="bg-gray-100 p-4 mb-4 rounded flex items-center">
        <div class="mr-3">
          <div class="mb-1">Modèle libre</div>
          <div class="text-xs text-gray-400">
            Je personnalise le contenu de ma mission.
          </div>
        </div>
        <el-button
          plain
          type="primary"
          class="ml-auto h-full"
          @click="handleSelectTemplate()"
          >Choisir</el-button
        >
      </div>
    </div>

    <!-- STEP 3 : formulaire -->
    <MissionForm v-else :mission="mission" :structureId="structureId" />
  </div>
</template>

<script>
import { fetchMissionTemplates, fetchTags } from '@/api/app'
import { getStructure } from '@/api/structure'
import MissionForm from '@/views/SNU/MissionForm'

export default {
  name: 'MissionAdd',
  components: {
    MissionForm,
  },
  props: {
    structureId: {
      type: Number,
      default: null,
    },
    mission: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      step: this.$router.history.current.query.step || 1,
      template_id: null,
      templates: [],
      domaine_id: null,
      domaines: [],
    }
  },
  async created() {
    if (this.step == 1) {
      let { data } = await fetchTags({ 'filter[type]': 'domaine' })
      this.domaines = data.data
    }
    if (this.step == 2) {
      fetchTags({ 'filter[type]': 'domaine' }).then((res) => {
        this.domaine_id =
          this.$router.history.current.query.domaine || res.data.data[0].id
        this.domaines = res.data.data
        fetchMissionTemplates({ 'filter[domaine.id]': this.domaine_id }).then(
          (res) => {
            this.templates = res.data.data
          }
        )
      })
    }
  },
  methods: {
    hangeSelectDomaine(domaine_id) {
      this.$router.push({
        name: 'MissionFormAdd',
        query: { step: 2, domaine: domaine_id },
      })
    },
    handleChangeDomaine(domaine_id) {
      fetchMissionTemplates({ 'filter[domaine.id]': domaine_id }).then(
        (res) => {
          this.templates = res.data.data
        }
      )
    },
    async handleSelectTemplate(template) {
      const { data } = await getStructure(this.structureId)
      this.$router.push({
        name: 'MissionFormAdd',
        params: {
          mission: {
            template_id: template ? template.id : null,
            template: template || null,
            state: template ? 'Validée' : 'En attente de validation', // TODO : cette logique devrait être côté PHP setState()
            tuteur_id: this.$store.getters.user.profile.id,
            structure_id: this.structureId,
            structure: data,
          },
        },
        query: { step: 3 },
      })
    },
  },
}
</script>
