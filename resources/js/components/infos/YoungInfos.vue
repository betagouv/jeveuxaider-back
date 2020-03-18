<template>
  <div class="">
    <div class="text-xs text-gray-600 flex flex-row flex-wrap">
      <div v-if="young.mission_type" class="card-item mb-2 flex">
        <div class="card-label">Type</div>
        <div class="text-gray-900 flex-1">{{ young.mission_type }}</div>
      </div>
      <div v-if="young.email" class="card-item mb-2 flex">
        <div class="card-label">Email</div>
        <div class="text-gray-900 flex-1">{{ young.email }}</div>
      </div>
      <div v-if="young.phone" class="card-item mb-2 flex">
        <div class="card-label">Téléphone</div>
        <div class="text-gray-900 flex-1">{{ young.phone }}</div>
      </div>
      <div v-if="young.department" class="card-item mb-2 flex">
        <div class="card-label">Département</div>
        <div class="text-gray-900 flex-1">
          {{ young.department | fullDepartmentFromValue }}
        </div>
      </div>
      <div v-if="young.regular_city" class="card-item mb-2 flex">
        <div class="card-label">Ville</div>
        <div class="text-gray-900 flex-1">{{ young.regular_city }}</div>
      </div>
      <div v-if="young.interet_defense" class="card-item mb-2 flex">
        <div class="card-label">Défense</div>
        <div class="text-gray-900 flex-1">{{ young.interet_defense }}</div>
      </div>
      <div v-if="young.interet_defense_type" class="card-item  mb-2 flex">
        <div class="card-label">Type</div>
        <div class="text-gray-900 flex-1">
          {{ young.interet_defense_type }}
        </div>
      </div>
      <div v-if="young.interet_defense_domaine" class="card-item mb-2 flex">
        <div class="card-label">Domaine</div>
        <div class="text-gray-900 flex-1">
          {{ young.interet_defense_domaine }}
        </div>
      </div>
      <div v-if="young.interet_securite" class="card-item mb-2 flex">
        <div class="card-label">Sécurité</div>
        <div class="text-gray-900 flex-1">{{ young.interet_securite }}</div>
      </div>
      <div v-if="young.interet_securite_domaine" class="card-item mb-2 flex">
        <div class="card-label">Domaine</div>
        <div class="text-gray-900 flex-1">
          {{ young.interet_securite_domaine }}
        </div>
      </div>
      <div v-if="young.interet_solidarite" class="card-item mb-2 flex">
        <div class="card-label">Solidarité</div>
        <div class="text-gray-900 flex-1">{{ young.interet_solidarite }}</div>
      </div>
      <div v-if="young.interet_sante" class="card-item mb-2 flex">
        <div class="card-label">Santé</div>
        <div class="text-gray-900 flex-1">{{ young.interet_sante }}</div>
      </div>
      <div v-if="young.interet_sport" class="card-item mb-2 flex">
        <div class="card-label">Sport</div>
        <div class="text-gray-900 flex-1">{{ young.interet_sport }}</div>
      </div>
      <div v-if="young.interet_education" class="card-item mb-2 flex">
        <div class="card-label">Éducation</div>
        <div class="text-gray-900 flex-1">{{ young.interet_education }}</div>
      </div>
      <div v-if="young.interet_culture" class="card-item mb-2 flex">
        <div class="card-label">Culture</div>
        <div class="text-gray-900 flex-1">{{ young.interet_culture }}</div>
      </div>
      <div v-if="young.interet_environnement" class="card-item mb-2 flex">
        <div class="card-label">Environnem.</div>
        <div class="text-gray-900 flex-1">
          {{ young.interet_environnement }}
        </div>
      </div>
      <div v-if="young.interet_citoyennete" class="card-item mb-2 flex">
        <div class="card-label">Citoyenneté</div>
        <div class="text-gray-900 flex-1">
          {{ young.interet_environnement }}
        </div>
      </div>
      <div v-if="young.contraintes" class="card-item mb-2 flex">
        <div class="card-label">Contraintes</div>
        <div class="text-gray-900 flex-1">{{ young.contraintes }}</div>
      </div>
      <div v-if="young.notes" class="card-item mb-2 flex">
        <div class="card-label">Notes</div>
        <div class="text-gray-900 flex-1">{{ young.notes }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "YoungInfos",
  props: {
    young: {
      type: Object,
      required: true
    }
  },
  data() {
    return {};
  },
  created() {},

  methods: {}
};
</script>
