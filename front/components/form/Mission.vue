<template>
  <div class="">
    <div class="flex">
      <div style="max-width: 600px">
        <template v-if="mode == 'add'">
          <div
            v-if="form.template"
            class="mb-6 text-md leading-snug text-gray-500"
          >
            En choisissant un modèle, certains champs de la mission sont
            prédéfinis et non modifiables : le titre, le domaine d'action
            principal, l'objectif et la description de la mission. Vous pouvez
            éditer les autres champs dont la localisation, les dates, le nombre
            de bénévoles recherchés et ajouter un commentaire pour préciser la
            mission. La mission est mise en ligne dès que vous décidez de la
            publier.
          </div>
          <div v-else class="mb-6 text-md leading-snug text-gray-500">
            En choisissant de rédiger intégralement votre mission, tous les
            champs de la mission sont éditables. La mission est publiée après
            validation par le référent départemental de JeVeuxAider.gouv.fr.
          </div>
        </template>

        <div class="mt-2 mb-6 text-xs leading-snug text-gray-500">
          <span>Une question? Contactez </span>
          <a
            target="_blank"
            href="mailto:contact@reserve-civique.on.crisp.email"
            class="font-bold"
          >
            le support</a
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
              />
            </div>

            <div class="w-full flex items-center p-4">
              <div class="mr-3">
                <div class="mb-1">{{ form.template.title }}</div>

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
              </div>

              <el-button
                plain
                type="primary"
                class="ml-3"
                @click.prevent="modalVisible = true"
                >Aperçu</el-button
              >
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
              <div class="flex-1 border-t-2 border-gray-200"></div>
            </div>
            <div class="mt-2">{{ form.template.subtitle }}</div>
            <div class="flex items-center mt-6">
              <h4
                class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
              >
                Objectif de la mission
              </h4>
              <div class="flex-1 border-t-2 border-gray-200"></div>
            </div>
            <div
              class="mt-2 break-normal"
              v-html="form.template.objectif"
            ></div>
            <div class="flex items-center mt-6">
              <h4
                class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
              >
                Description de la mission et règles à suivre impérativement
              </h4>
              <div class="flex-1 border-t-2 border-gray-200"></div>
            </div>
            <div class="mt-2" v-html="form.template.description"></div>
          </el-dialog>
        </div>

        <el-form
          ref="missionForm"
          :model="form"
          class="max-w-2xl"
          label-position="top"
          :rules="rules"
        >
          <div class="mt-6 mb-6 text-xl text-gray-800">
            Détails de la mission
          </div>
          <div v-if="!form.template">
            <el-form-item
              label="Titre de la mission"
              prop="name"
              class="flex-1 mr-2"
            >
              <ItemDescription container-class="mb-3">
                Le titre de la mission doit être une phrase qui précise l'action
                du bénévole, par exemple "Je fais les courses de produits
                essentiels pour mes voisins les plus fragiles".
              </ItemDescription>
              <el-input
                v-model="form.name"
                placeholder="Décrivez l'action du bénévole en une phrase"
              />
            </el-form-item>
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
                ></el-option>
              </el-select>
            </el-form-item>

            <div v-if="mainDomaineId" class="el-form-item is-required">
              <MissionThumbnailPicker
                :domain-id="mainDomaineId"
                :value="`${form.thumbnail}`"
                @click="onThumbnailClick"
              />
            </div>

            <el-form-item
              label="Objectif de la mission"
              prop="objectif"
              class="flex-1"
            >
              <ItemDescription container-class="mb-3">
                Décrivez les enjeux et la finalité de la mission.
              </ItemDescription>
              <RichEditor v-model="form.objectif" />
            </el-form-item>
            <el-form-item
              label="Description et règles à appliquer"
              prop="description"
              class="flex-1"
            >
              <ItemDescription container-class="mb-3">
                Décrivez précisément le rôle et les activités du bénévole.
              </ItemDescription>
              <RichEditor v-model="form.description" />
            </el-form-item>
          </div>

          <el-form-item label="Type de mission" prop="type">
            <el-select
              v-model="form.type"
              placeholder="Sélectionner un type de mission"
              @change="handleTypeChanged()"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.mission_types.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>

          <el-form-item label="Format de mission" prop="format">
            <el-select
              v-model="form.format"
              placeholder="Sélectionner un format de mission"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.mission_formats.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>

          <el-form-item label="Domaines d'action complémentaires" prop="tags">
            <ItemDescription container-class="mb-3">
              Le choix d'un ou plusieurs domaines d'action complémentaires
              permettra à votre mission d'être référencée dans les domaines
              d'action correspondant lors d'une recherche par les réservistes
            </ItemDescription>
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
              ></el-option>
            </el-select>
          </el-form-item>

          <el-form-item
            label="Un mot pour motiver les bénévoles à participer"
            prop="information"
            class="flex-1"
          >
            <ItemDescription container-class="mb-3">
              Informations complémentaires à l'attention du bénévole.
            </ItemDescription>
            <RichEditor v-model="form.information" />
          </el-form-item>

          <el-form-item
            label="Nombre de bénévoles susceptibles d’être accueillis de façon concomitante sur cette mission"
            prop="participations_max"
          >
            <ItemDescription container-class="mb-3">
              Précisez ce nombre en fonction de vos contraintes logistiques et
              votre capacité à accompagner les bénévoles.
            </ItemDescription>
            <el-input-number
              v-model="form.participations_max"
              :step="1"
              :min="1"
              class="w-full"
            />
          </el-form-item>

          <el-form-item
            label="Publics bénéficiaires"
            prop="publics_beneficiaires"
          >
            <el-select
              v-model="form.publics_beneficiaires"
              placeholder="Sélectionner les publics bénéficiaires"
              multiple
            >
              <el-option
                v-for="item in $store.getters.taxonomies
                  .mission_publics_beneficiaires.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <div class="mt-12 mb-6 text-xl text-gray-800">
            Dates de la mission
          </div>

          <div class="flex">
            <el-form-item
              label="Date de début"
              prop="start_date"
              class="flex-1 mr-2"
            >
              <el-date-picker
                v-model="form.start_date"
                class="w-full"
                type="datetime"
                placeholder="Date de début"
                value-format="yyyy-MM-dd HH:mm:ss"
                default-time="09:00:00"
                :picker-options="{ firstDayOfWeek: 1 }"
              />
            </el-form-item>
            <el-form-item
              label="Date de fin"
              prop="start_date"
              class="flex-1 ml-2"
            >
              <el-date-picker
                v-model="form.end_date"
                class="w-full"
                type="datetime"
                placeholder="Date de fin"
                default-time="18:00:00"
                value-format="yyyy-MM-dd HH:mm:ss"
                :picker-options="{ firstDayOfWeek: 1 }"
              />
            </el-form-item>
          </div>

          <div class="mt-12 mb-6 flex text-xl text-gray-800">
            Le lieu où se déroule la mission
          </div>

          <ItemDescription container-class="mb-6">
            Recruter au plus près du lieu de mission et des bénéficiaires permet
            de faciliter l'engagement des bénévoles. Vous avez la possibilité de
            dupliquer cette mission sur plusieurs lieux.
          </ItemDescription>

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
            :value="form.full_address"
            @selected="setAddress"
            @clear="clearAddress"
          />
          <el-form-item label="Adresse" prop="address">
            <el-input v-model="form.address" disabled placeholder="Adresse" />
          </el-form-item>
          <div class="flex">
            <el-form-item label="Code postal" prop="zip" class="flex-1 mr-2">
              <el-input v-model="form.zip" disabled placeholder="Code postal" />
            </el-form-item>
            <el-form-item label="Ville" prop="city" class="flex-1">
              <el-input v-model="form.city" disabled placeholder="Ville" />
            </el-form-item>
          </div>
          <div class="flex hidden">
            <el-form-item label="Latitude" prop="latitude" class="flex-1 mr-2">
              <el-input
                v-model="form.latitude"
                disabled
                placeholder="Latitude"
              />
            </el-form-item>
            <el-form-item label="Longitude" prop="longitude" class="flex-1">
              <el-input
                v-model="form.longitude"
                disabled
                placeholder="Longitude"
              />
            </el-form-item>
          </div>

          <div class="mt-12 mb-6 flex text-xl text-gray-800">
            Responsable de la mission
          </div>
          <ItemDescription container-class="mb-6">
            Les notifications lors de la prise de contact d'un bénévole
            concernant cette mission seront envoyées à cette personne. Vous
            pouvez également
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
              v-model="form.responsable_id"
              placeholder="Sélectionner un responsable"
            >
              <el-option
                v-for="item in form.structure.members"
                :key="item.id"
                :label="item.full_name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <div class="flex pt-2">
            <el-button type="primary" :loading="loading" @click="onSubmit"
              >Enregistrer</el-button
            >
          </div>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import FormWithAddress from '@/mixins/FormWithAddress'

export default {
  mixins: [FormWithAddress],
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
      loading: false,
      modalVisible: false,
      form: {
        participations_max: 1,
        ...this.mission,
      },
      domaines: [],
      rules: {
        name: [
          {
            required: true,
            message: "Veuillez choisir un domaine d'action",
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
        format: [
          {
            required: true,
            message: 'Veuillez choisir un format de mission',
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
      },
    }
  },
  computed: {
    mode() {
      return this.mission.id ? 'edit' : 'add'
    },
    mainDomaineId() {
      return this.form.template
        ? this.form.template.domaine.id
        : this.form.domaine_id
    },
    secondaryDomaines() {
      return this.domaines.filter((item) => item.id != this.mainDomaineId)
    },
  },
  created() {
    this.$api.fetchTags({ 'filter[type]': 'domaine' }).then((response) => {
      this.domaines = response.data.data
    })
    // Only if not a template
    if (!this.form.thumbnail && !this.form.template) {
      this.$set(this.form, 'thumbnail', `${this.mainDomaineId}_1`)
    }
  },
  methods: {
    onSubmit() {
      this.addOrUpdateMission()
    },
    addOrUpdateMission() {
      this.loading = true
      this.$refs.missionForm.validate((valid) => {
        if (valid) {
          if (this.mission.id) {
            this.$api
              .updateMission(this.mission.id, this.form)
              .then(() => {
                this.loading = false
                this.$router.go(-1)
                this.$message.success({
                  message: 'La mission a été mise à jour !',
                })
              })
              .catch(() => {
                this.loading = false
              })
          } else if (this.structureId) {
            this.$api
              .addStructureMission(this.structureId, this.form)
              .then(() => {
                this.loading = false
                this.$router.push(`/dashboard/missions`)
                this.$message.success({
                  message: 'La mission a été ajoutée !',
                })
              })
              .catch(() => {
                this.loading = false
              })
          }
        } else {
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
            cancelButtonText: 'Annuler',
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
</style>
