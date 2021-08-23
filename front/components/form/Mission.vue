<template>
  <div v-if="hasBeenInitialized" class="max-w-3xl">
    <template v-if="mode == 'add'">
      <div v-if="form.template" class="mb-6 text-md leading-snug text-gray-500">
        En choisissant un modèle, certains champs de la mission sont prédéfinis
        et non modifiables : le titre, le domaine d'action principal, l'objectif
        et la description de la mission. Vous pouvez éditer les autres champs
        dont la localisation, les dates, le nombre de bénévoles recherchés et
        ajouter un commentaire pour préciser la mission. La mission est mise en
        ligne dès que vous décidez de la publier.
      </div>
      <div v-else class="mb-6 text-md leading-snug text-gray-500">
        En choisissant de rédiger intégralement votre mission, tous les champs
        de la mission sont éditables. La mission est publiée après validation
        par le référent départemental de JeVeuxAider.gouv.fr.
      </div>
    </template>

    <div class="mt-2 mb-8 text-xs leading-snug text-gray-500">
      <span>Une question? Contactez </span>
      <a
        target="_blank"
        href="mailto:contact@reserve-civique.on.crisp.email"
        class="font-bold hover:underline"
        >le support</a
      >
      <span> ou chatez en cliquant sur le bouton en bas à droite.</span>
    </div>

    <div v-if="form.template">
      <div
        class="bg-gray-100 mb-4 rounded flex items-center overflow-hidden"
        style="height: 120px"
      >
        <div class="flex-none self-stretch">
          <img
            :src="`/images/templates/${form.template_id}.jpg`"
            :srcset="`/images/templates/${form.template_id}@2x.jpg 2x`"
            width="125px"
            class="object-cover h-full"
            @error="defaultThumbnail($event)"
          />
        </div>

        <div class="w-full flex items-center p-4">
          <div class="mr-3">
            <div class="mb-1">
              {{ form.template.title }}
            </div>

            <client-only>
              <v-clamp
                :max-lines="3"
                autoresize
                class="relative text-xs text-gray-400"
              >
                {{ form.template.subtitle }}

                <template slot="after" slot-scope="{ clamped }">
                  <span
                    v-if="clamped"
                    v-tooltip="{
                      delay: { show: 700, hide: 100 },
                      content: form.template.subtitle,
                    }"
                    class="absolute w-full h-full top-0 left-0"
                  />
                </template>
              </v-clamp>
            </client-only>
          </div>

          <el-button
            plain
            type="primary"
            class="ml-3"
            @click.prevent="modalVisible = true"
          >
            Aperçu
          </el-button>
        </div>
      </div>

      <el-dialog
        :title="form.template.title"
        :visible.sync="modalVisible"
        width="680"
      >
        <div class="flex items-center">
          <h4
            class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
          >
            Titre de la mission
          </h4>
          <div class="flex-1 border-t-2 border-gray-200" />
        </div>
        <div class="mt-2">
          {{ form.template.subtitle }}
        </div>
        <div class="flex items-center mt-6">
          <h4
            class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
          >
            Présentation de la mission
          </h4>
          <div class="flex-1 border-t-2 border-gray-200" />
        </div>
        <div class="mt-2 break-normal" v-html="form.template.objectif" />
        <div class="flex items-center mt-6">
          <h4
            class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
          >
            Précisions
          </h4>
          <div class="flex-1 border-t-2 border-gray-200" />
        </div>
        <div class="mt-2" v-html="form.template.description" />
      </el-dialog>
    </div>

    <el-form
      ref="missionForm"
      :model="form"
      class=""
      label-position="top"
      :rules="rules"
    >
      <div class="mt-6 mb-6 text-1-5xl font-bold text-gray-800">
        Descriptif de la mission
      </div>
      <div v-if="!form.template">
        <el-form-item label="Titre de la mission" prop="name" class="flex-1">
          <ItemDescription container-class="mb-3">
            Le titre de la mission doit être une phrase qui précise l'action du
            bénévole.<br />
            Exemple : Je fais les courses de produits essentiels pour mes
            voisins les plus fragiles
          </ItemDescription>

          <el-input
            v-model="form.name"
            placeholder="Décrivez l'action du bénévole en une phrase"
          />
        </el-form-item>

        <div class="grid grid-cols-2 gap-4">
          <el-form-item
            label="Domaine d'action principal"
            prop="domaine_id"
            class="flex-1"
          >
            <el-select
              v-model="form.domaine_id"
              placeholder="Sélectionner un domaine d'action"
              @change="$set(form, 'thumbnail', `${form.domaine_id}_1`)"
            >
              <el-option
                v-for="domaine in domaines"
                :key="domaine.id"
                :label="domaine.name.fr"
                :value="domaine.id"
              />
            </el-select>
          </el-form-item>

          <el-form-item
            label="Domaine d'action secondaire"
            prop="domaine_secondaire"
          >
            <el-select
              v-model="form.domaine_secondaire"
              placeholder="Sélectionner le domaine"
              clearable
              value-key="id"
            >
              <el-option
                v-for="domaine in secondaryDomaines"
                :key="domaine.value.id"
                :label="domaine.label"
                :value="domaine.value"
              />
            </el-select>
          </el-form-item>

          <!-- <el-form-item label="Domaines d'action complémentaires" prop="tags">
            <el-select
              v-model="form.tags"
              filterable
              multiple
              :multiple-limit="3"
              placeholder="Sélectionner les domaines d'action complémentaires"
            >
              <el-option
                v-for="domaine in secondaryDomaines"
                :key="domaine.id"
                :label="domaine.name.fr"
                :value="domaine.name.fr"
              />
            </el-select>
          </el-form-item> -->
        </div>

        <div v-if="mainDomaineId" class="el-form-item is-required">
          <MissionThumbnailPicker
            :domain-id="mainDomaineId"
            :value="`${form.thumbnail}`"
            @click="onThumbnailClick"
          />
        </div>

        <el-form-item
          label="Présentation de la mission"
          prop="objectif"
          class="flex-1"
        >
          <ItemDescription container-class="mb-3">
            Présentez en quelques mots le contexte dans lequel s'inscrit
            l'intervention du bénévole (historique et objectifs de la mission).
          </ItemDescription>

          <RichEditor v-model="form.objectif" />
        </el-form-item>

        <el-form-item
          id="precisions"
          label="Précisions"
          prop="description"
          class="flex-1"
        >
          <ItemDescription container-class="mb-3">
            Décrivez les modalités concrètes de l'intervention du bénévole
            (rôle, actions à réaliser, modalités d'organisation de la mission).
          </ItemDescription>

          <RichEditor v-model="form.description" />
        </el-form-item>
      </div>

      <el-form-item
        label="Quelques mots pour motiver les bénévoles à participer"
        prop="information"
        class="flex-1"
      >
        <RichEditor v-model="form.information" />
      </el-form-item>

      <el-form-item label="Publics bénéficiaires" prop="publics_beneficiaires">
        <el-checkbox-group v-model="form.publics_beneficiaires">
          <el-checkbox
            v-for="item in $store.getters.taxonomies
              .mission_publics_beneficiaires.terms"
            :key="item.value"
            :label="item.value"
            border
            >{{ item.label }}</el-checkbox
          >
        </el-checkbox-group>
      </el-form-item>

      <el-form-item
        label="Nombre de bénévoles recherchés durant la mission"
        prop="participations_max"
      >
        <div class="flex items-center gap-4">
          <el-input-number
            v-model="form.participations_max"
            :step="1"
            :step-strictly="true"
            :min="1"
            :max="1000000"
          />
          <span class="flex-none" style="width: 150px">
            {{
              form.participations_max
                | pluralize(['bénévole recherché', 'bénévoles recherchés'])
            }}
          </span>
        </div>
      </el-form-item>

      <el-form-item
        label="Compétences recherchées (facultatif)"
        prop="skills"
        class="form-item--skills"
      >
        <ItemDescription container-class="mb-6">
          Vous pouvez préciser jusqu'à 3 compétences
        </ItemDescription>

        <AlgoliaSkillsInput
          ref="algoliaSkillsInput"
          :items="form.skills"
          :max="3"
          placeholder="Exemples: communication, action sociale, accompagnement..."
          @add-item="handleSkillSelected($event)"
        />

        <div v-if="form.skills.length" class="mt-4 leading-relaxed">
          <div class="flex flex-wrap gap-4">
            <div
              v-for="item in form.skills"
              :key="item.id"
              class="flex items-center space-x-4 py-2 pl-3 pr-2 rounded-10 border border-blue-800 bg-white"
            >
              <div class="flex-none text-sm text-blue-800 font-bold">
                {{ item.name.fr }}
              </div>
              <div
                class="flex-none cursor-pointer w-6 h-6 p-1 transform will-change-transform text-blue-800 hover:text-red-700 hover:scale-125 transition ease-in-out duration-150"
                @click="handleRemoveSkill(item.id)"
                v-html="
                  require('@/assets/images/icones/heroicon/close.svg?include')
                "
              />
            </div>
          </div>
        </div>
      </el-form-item>

      <el-form-item
        label="Mission également ouverte aux"
        prop="publics_volontaires"
      >
        <el-checkbox-group v-model="form.publics_volontaires">
          <el-checkbox
            v-for="item in $store.getters.taxonomies.mission_publics_volontaires
              .terms"
            :key="item.value"
            :label="item.value"
            border
            >{{ item.label }}</el-checkbox
          >
        </el-checkbox-group>
      </el-form-item>

      <div class="mt-12 mb-6 text-1-5xl font-bold text-gray-800">
        Dates de la mission
      </div>

      <div class="grid grid-cols-2 gap-4">
        <el-form-item label="Date de début" prop="start_date">
          <el-date-picker
            v-model="form.start_date"
            class="w-full"
            type="datetime"
            placeholder="Date de début"
            format="dd MMMM yyyy à H[h]mm"
            value-format="yyyy-MM-dd HH:mm:ss"
            default-time="09:00:00"
            :picker-options="{ firstDayOfWeek: 1 }"
          />
        </el-form-item>

        <el-form-item label="Date de fin (facultatif)" prop="end_date">
          <el-date-picker
            v-model="form.end_date"
            class="w-full"
            type="datetime"
            placeholder="Date de fin"
            default-time="18:00:00"
            format="dd MMMM yyyy à H[h]mm"
            value-format="yyyy-MM-dd HH:mm:ss"
            :picker-options="{ firstDayOfWeek: 1 }"
          />
        </el-form-item>
      </div>

      <div class="flex flex-wrap sm:flex-no-wrap items-center gap-4 mb-8">
        <el-form-item
          label="Durée d'engagement minimum"
          prop="commitment__duration"
          class="mb-0 flex-none"
        >
          <el-select
            v-model="form.commitment__duration"
            placeholder="Choisissez une durée"
            clearable
          >
            <el-option
              v-for="item in $store.getters.taxonomies.duration.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>

        <span class="flex-none pt-5 h-10"> par </span>

        <el-form-item
          label="Fréquence (facultatif)"
          prop="commitment_frequency"
          class="mb-0 w-full"
        >
          <el-select
            v-model="form.commitment__time_period"
            placeholder="Choisissez une fréquence"
            clearable
          >
            <el-option
              v-for="item in $store.getters.taxonomies.time_period.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </div>

      <div class="mt-6 mb-6 text-1-5xl font-bold text-gray-800">
        Lieu de la mission
      </div>

      <el-form-item prop="type">
        <el-radio-group v-model="form.type" @change="handleTypeChanged()">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.mission_types.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-radio-button>
        </el-radio-group>

        <ItemDescription container-class="mt-6">
          <template v-if="form.type == 'Mission en présentiel'">
            Recruter au plus près du lieu de mission et des bénéficiaires permet
            de faciliter l'engagement des bénévoles. Vous avez la possibilité de
            dupliquer cette mission sur plusieurs lieux.
          </template>
          <template v-else>
            Le volontaire est donc invité à réaliser la mission en autonomie, à
            domicile ou près de chez lui.
            <template v-if="!form.template_id">
              <br />N’hésitez pas à en dire davantage dans le champ
              <a href="#precisions" class="underline">Précisions</a>.
            </template>
          </template>
        </ItemDescription>
      </el-form-item>

      <template v-if="form.type == 'Mission en présentiel'">
        <el-form-item label="Département" prop="department">
          <el-select
            v-model="form.department"
            filterable
            placeholder="Département"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.departments.terms"
              :key="item.value"
              :label="`${item.value} - ${item.label}`"
              :value="item.value"
            />
          </el-select>
        </el-form-item>

        <AlgoliaPlacesInput
          ref="alogoliaInput"
          :initial-value="form.full_address"
          :label="`Rechercher l'adresse du lieu de la mission`"
          @selected="setAddress"
          @clear="clearAddress"
        />

        <div class="grid grid-cols-6 gap-4">
          <el-form-item label="Adresse" prop="address" class="col-span-3">
            <el-input v-model="form.address" disabled placeholder="Adresse" />
          </el-form-item>

          <el-form-item label="Code postal" prop="zip" class="col-span-1">
            <el-input v-model="form.zip" disabled placeholder="Code postal" />
          </el-form-item>

          <el-form-item label="Ville" prop="city" class="col-span-2">
            <el-input v-model="form.city" disabled placeholder="Ville" />
          </el-form-item>
        </div>

        <div class="hidden">
          <el-form-item label="Latitude" prop="latitude">
            <el-input v-model="form.latitude" disabled placeholder="Latitude" />
          </el-form-item>

          <el-form-item label="Longitude" prop="longitude">
            <el-input
              v-model="form.longitude"
              disabled
              placeholder="Longitude"
            />
          </el-form-item>
        </div>
      </template>

      <div class="mt-6 mb-6 text-1-5xl font-bold text-gray-800">
        Responsable de la mission
      </div>
      <ItemDescription container-class="mb-6">
        Les notifications lors de la prise de contact d'un bénévole concernant
        cette mission seront envoyées à cette personne. Vous pouvez également
        <nuxt-link
          target="_blank"
          :to="`/dashboard/structure/${structureId}/members`"
          class="underline cursor-pointer"
        >
          ajouter un nouveau membre
        </nuxt-link>
        à votre équipe.
      </ItemDescription>

      <el-form-item
        v-if="form.structure"
        label="Responsable"
        prop="responsable_id"
        class="flex-1"
      >
        <el-select
          v-model="form.responsable"
          placeholder="Sélectionner un responsable"
          value-key="id"
          @change="form.responsable_id = $event.id"
        >
          <el-option
            v-for="member in members"
            :key="member.value.id"
            :label="member.label"
            :value="member.value"
          />
        </el-select>
      </el-form-item>

      <hr class="mt-16" />

      <div class="flex items-start gap-4 pt-8">
        <div
          v-if="
            form.state == 'Brouillon' &&
            $store.getters.user.context_role != 'admin'
          "
          class="flex flex-col items-center"
        >
          <el-button
            type="secondary"
            :loading="loading"
            class="el-button--submit"
            @click="onSubmit('Brouillon')"
          >
            Enregistrer en brouillon
          </el-button>

          <nuxt-link
            v-if="mission.id"
            target="_blank"
            :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
            class="text-xs text-gray-500 hover:underline mt-2"
          >
            Aperçu de la mission ›
          </nuxt-link>
        </div>

        <el-button
          v-if="form.template_id && ['Brouillon'].includes(form.state)"
          type="success"
          :loading="loading"
          class="el-button--submit"
          @click="onSubmit('Validée')"
        >
          Enregistrer et publier
        </el-button>

        <el-button
          v-else-if="
            ['En attente de validation', 'Brouillon'].includes(form.state) &&
            $store.getters.user.context_role != 'admin'
          "
          type="success"
          :loading="loading"
          class="el-button--submit"
          @click="onSubmit('En attente de validation')"
        >
          Soumettre à validation
        </el-button>

        <el-button
          v-else
          type="success"
          :loading="loading"
          class="el-button--submit"
          @click="onSubmit()"
        >
          Enregistrer
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'
import FormWithAddress from '@/mixins/FormWithAddress'
import MissionMixin from '@/mixins/MissionMixin'

export default {
  mixins: [FormMixin, FormWithAddress, MissionMixin],
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
      hasBeenInitialized: false,
      loading: false,
      modalVisible: false,
      form: {
        participations_max: 1,
        skills: [],
        state: 'Brouillon',
        type: 'Mission en présentiel',
        ...this.mission,
        publics_beneficiaires: this.mission.publics_beneficiaires ?? [],
        publics_volontaires: this.mission.publics_volontaires ?? [],
      },
      domaines: [],
      rules: {
        name: [
          {
            required: true,
            message: 'Veuillez choisir un titre',
            trigger: 'blur',
          },
        ],
        domaine_id: [
          {
            required: true,
            message: "Veuillez choisir un domaine d'action principal",
            trigger: 'blur',
          },
        ],
        type: [
          {
            required: true,
            message: 'Veuillez choisir un type de mission',
            trigger: 'blur',
          },
        ],
        publics_beneficiaires: [
          {
            required: true,
            message: 'Veuillez choisir au moins un public bénéficiaire',
            trigger: 'blur',
          },
        ],
        objectif: [
          {
            required: true,
            message: 'Veuillez renseigner un objectif de la mission',
            trigger: 'blur',
          },
        ],
        description: [
          {
            required: true,
            message: 'Veuillez renseigner un descriptif de la mission',
            trigger: 'blur',
          },
        ],
        department: [
          {
            required: true,
            message: 'Veuillez sélectionner un département',
            trigger: 'blur',
          },
        ],
        address: [
          {
            required: true,
            message: 'Veuillez renseigner une adresse',
            trigger: 'blur',
          },
        ],
        city: [
          {
            required: true,
            message: 'Veuillez renseigner un ville',
            trigger: 'blur',
          },
        ],
        responsable_id: [
          {
            required: true,
            message: 'Veuillez sélectionner le responsable de la mission',
            trigger: 'blur',
          },
        ],
        start_date: [
          {
            required: true,
            message: 'La date de début est requise',
          },
        ],
        commitment__duration: [
          {
            required: true,
            message: "La durée d'engagement minimum est requise",
          },
        ],
      },
    }
  },
  computed: {
    mode() {
      return this.mission.id ? 'edit' : 'add'
    },
    mainDomaineId() {
      return this.form.template
        ? this.form.template.domaine_id
        : this.form.domaine_id
    },
    secondaryDomaines() {
      const secondaryDomains = this.domaines.filter(
        (item) => item.id != this.mainDomaineId
      )
      return secondaryDomains.map((domain) => {
        return { label: domain.name.fr, value: domain }
      })
    },
    members() {
      const members = this.form.structure.members
      return members.map((member) => {
        return { label: member.full_name, value: member }
      })
    },
    messageOnSubmit() {
      let message
      switch (this.form.state) {
        case 'Brouillon':
          message = 'La mission a été enregistrée en tant que brouillon.'
          break
        case 'En attente de validation':
          message =
            'Les modifications ont été enregistrées.\r\nVotre mission sera modérée très prochainement.'
          break
        case 'Validée':
          message = !this.form.id
            ? 'La mission a été ajoutée !'
            : 'La mission a été mise à jour !'
          break
        default:
          message = 'La mission a été enregistrée.'
          break
      }

      return message
    },
  },
  watch: {
    'form.type'(newVal) {
      if (newVal == 'Mission à distance') {
        this.setAddressFromStructure()
      }
    },
  },
  async created() {
    const domaines = await this.$api.fetchTags({ 'filter[type]': 'domaine' })
    this.domaines = domaines.data.data

    // Only if not a template
    if (!this.form.thumbnail && !this.form.template) {
      this.$set(this.form, 'thumbnail', `${this.mainDomaineId}_1`)
    }

    // ONLY FORM ADD
    if (!this.form.id) {
      const structure = await this.$api.getStructure(this.$route.params.id)
      this.$set(this.form, 'structure', structure)

      const responsable =
        structure.members.find(
          (member) => member.id == parseInt(this.$store.getters.user.profile.id)
        ) ?? structure.members[0]
      this.$set(this.form, 'responsable_id', parseInt(responsable.id))
      this.$set(this.form, 'responsable', responsable)

      this.$set(this.form, 'structure_id', parseInt(this.$route.params.id))
      if (this.$route.query.template) {
        const template = await this.$api.getMissionTemplate(
          this.$route.query.template
        )
        this.$set(this.form, 'template_id', template.id)
        this.$set(this.form, 'template', template)
      }

      this.setAddressFromStructure()
    }

    this.hasBeenInitialized = true
  },
  methods: {
    onSubmit(state = this.form.state) {
      this.loading = true
      this.$refs.missionForm.validate((valid, fields) => {
        if (valid) {
          if (this.mission.id) {
            this.$api
              .updateMission(this.mission.id, { ...this.form, state })
              .then((updatedMission) => {
                this.form.state = updatedMission.data.state
                this.loading = false
                this.$router.go(-1)
                this.$message.success({
                  message: this.messageOnSubmit,
                })
              })
              .catch(() => {
                this.loading = false
              })
          } else if (this.structureId) {
            this.$api
              .addStructureMission(this.structureId, { ...this.form, state })
              .then((updatedMission) => {
                this.form.state = updatedMission.data.state
                this.loading = false
                this.$router.push(
                  `/dashboard/structure/${this.structureId}/missions`
                )
                this.$message.success({ message: this.messageOnSubmit })
              })
              .catch(() => {
                this.loading = false
              })
          }
        } else {
          this.showErrors(fields)
          this.loading = false
        }
      })
    },
    handleTypeChanged() {
      if (this.form.type == 'Mission en présentiel') {
        this.$confirm(
          'Veillez à respecter les règles de sécurité pour les missions en présentiel.<br>',
          'Confirmation',
          {
            confirmButtonText: 'Je confirme',
            showCancelButton: false,
            type: 'warning',
            center: true,
            dangerouslyUseHTMLString: true,
          }
        )
      }
    },
    onThumbnailClick(thumbnail) {
      this.$set(this.form, 'thumbnail', thumbnail)
    },
    handleSkillSelected(payload) {
      this.$set(this.form, 'skills', [...this.form.skills, payload])
    },
    handleRemoveSkill(id) {
      this.form.skills = this.form.skills.filter((item) => item.id !== id)
    },
    setAddressFromStructure() {
      this.form.address = this.form.structure.address
      this.form.city = this.form.structure.city
      this.form.zip = this.form.structure.zip
      this.form.department = this.form.structure.department
      this.form.full_address = `${this.form.address} ${this.form.zip} ${this.form.city}`
      this.form.latitude = this.form.structure.latitude
      this.form.longitude = this.form.structure.longitude
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep
  .el-input-number__decrease,
  .el-input-number__increase
    bottom: 1px
    display: flex
    align-items: center
    justify-content: center

.el-checkbox-group
  @apply flex flex-wrap gap-4
  > label
    @apply m-0 rounded-10 #{!important}
    @apply ease-in-out duration-150 transition
    &:hover
      @apply border-primary

.form-item--skills
  ::v-deep
    #autosuggest
      input
        @apply rounded-10 leading-relaxed py-2
      .after-input
        top: 10px !important

.el-radio-button
  ::v-deep .el-radio-button__inner
    @apply px-16 #{!important}

.el-button--submit
  border-radius: 10px
  font-weight: bold
  padding: 16px 32px
  font-size: 18px
  letter-spacing: -1px
</style>
