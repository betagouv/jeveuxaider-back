<template>
  <div class="max-w-3xl">
    <div class="mb-6 text-md leading-snug text-gray-500">
      Vous pouvez publier une mission de bénévolat à partir d'un modèle
      prédéfini ou choisir de la rédiger intégralement dans le respect de la
      <nuxt-link class="text-primary" to="/charte-reserve-civique"
        >charte</nuxt-link
      >.
    </div>
    <el-select
      v-model="domaine_id"
      placeholder="Domaine d'action"
      class="mb-8"
      @change="$emit('change-domaine', domaine_id)"
    >
      <el-option
        v-for="domaine in domaines"
        :key="domaine.id"
        :label="domaine.name.fr"
        :value="domaine.id"
      ></el-option>
    </el-select>

    <div class="text-1-5xl font-bold text-gray-800 flex items-center">
      <div>Rédiger intégralement la mission</div>
      <el-tag type="warning" size="small" class="ml-2"
        >Validation par un référent</el-tag
      >
    </div>
    <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
      <i class="el-icon-info text-primary mt-1 mr-2"></i>
      <div class="flex-1">
        En choisissant de rédiger intégralement votre mission, tous les champs
        sont éditables. La mission est publiée après validation par le référent
        départemental de JeVeuxAider.gouv.fr.
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
        @click="$emit('selected')"
        >Choisir</el-button
      >
    </div>

    <div
      class="mt-10 mb-6 text-1-5xl font-bold text-gray-800 flex items-center"
    >
      <div>Publier une mission à partir d'un modèle</div>
      <el-tag type="success" size="small" class="ml-2"
        >Publication automatique</el-tag
      >
    </div>
    <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
      <i class="el-icon-info text-primary mt-1 mr-2"></i>
      <div class="flex-1">
        En choisissant un modèle, vous publiez une mission plus rapidement. La
        plupart des champs sont prédéfinis et la mission est mise en ligne dès
        que vous décidez de la publier.
      </div>
    </div>

    <template v-if="templates.length > 0">
      <div
        v-for="template in templates"
        :key="template.label"
        class="bg-gray-100 mb-4 rounded flex items-center overflow-hidden"
        style="height: 120px"
      >
        <div class="flex-none self-stretch">
          <img
            :src="`/images/templates/${template.id}.jpg`"
            :srcset="`/images/templates/${template.id}@2x.jpg 2x`"
            width="125px"
            class="object-cover h-full"
            @error="defaultThumbnail($event)"
          />
        </div>

        <div class="w-full flex items-center p-4">
          <div class="mr-3">
            <div class="mb-1">{{ template.title }}</div>
            <client-only :placeholder="template.subtitle">
              <v-clamp
                :max-lines="3"
                autoresize
                class="relative text-xs text-gray-400"
              >
                {{ template.subtitle }}

                <template slot="after" slot-scope="{ clamped }">
                  <!-- Tooltip if clamped -->
                  <span
                    v-if="clamped"
                    v-tooltip="{
                      delay: { show: 700, hide: 100 },
                      content: template.subtitle,
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
            size="medium"
            class="ml-auto h-full"
            @click="$emit('selected', template)"
            >Choisir</el-button
          >
        </div>
      </div>
    </template>
    <template v-else>
      <div class="bg-gray-100 p-4 mb-4 rounded flex items-center">
        <div class="mr-3">
          <div class="mb-1">
            Aucun modèle de mission dans ce domaine pour l'instant
          </div>
          <div class="text-xs text-gray-400">
            Choisissez de rédiger votre mission ci-dessus
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import MissionMixin from '@/mixins/MissionMixin'

export default {
  mixins: [MissionMixin],
  props: {
    domaineId: {
      type: Number,
      required: true,
    },
    domaines: {
      type: Array,
      required: true,
    },
    templates: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      domaine_id: this.domaineId,
    }
  },
  methods: {},
}
</script>
