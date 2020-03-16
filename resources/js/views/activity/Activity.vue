<template>
  <div v-if="!loading">
    <div class="header flex justify-between">
      <div class="mb-10">
        <div class="flex items-center">
          <h1 class="text-3xl mr-4">{{ activity.libelle }}</h1>
          <activity-status-tag v-if="activity.statut" :status="activity.statut"></activity-status-tag>
        </div>
        <div class="font-light text-gray-600 text-xl mb-3">
          <span v-if="activity.type_activite">{{ activity.type_activite }}</span>
          <span v-if="activity.nature_activite">- {{ activity.nature_activite }}</span>
        </div>
        <div
          v-if="activity.debut_projet_annee"
          class="font-light text-gray-600"
        >{{ activity.debut_projet_annee }}-{{ activity.debut_projet_trimestre }} au {{ activity.fin_projet_annee }}-{{ activity.debut_projet_trimestre }}</div>
      </div>
      <div class="actions">
        <el-button type="primary" plain @click="$router.back()">Retour</el-button>
        <router-link :to="{ name: 'activity.edit', params: { id: activity.id } }">
          <el-button type="primary">
            <i class="el-icon-edit mr-1 font-bold"></i> Modifier
          </el-button>
        </router-link>
      </div>
    </div>
    <div class>
      <div class="text-2xl mb-4">Informations générales</div>
      <el-card class="mb-10">
        <div class="field">
          <div class="field-label">Type d'activité</div>
          <div class="field-value">{{ activity.type_activite }}</div>
        </div>
        <div class="field">
          <div class="field-label">Nature de l'activité</div>
          <div class="field-value">{{ activity.nature_activite }}</div>
        </div>
        <div class="field" v-if="activity.type_activite == 'Projet'">
          <div class="field-label">Projet chapeau</div>
          <div class="field-value">{{ activity.projet_chapeau }}</div>
        </div>
        <div class="field">
          <div class="field-label">Contraintes</div>
          <div class="field-value">{{ activity.contrainte_reg_ou_tech }}</div>
        </div>
        <div class="field">
          <div class="field-label">Description</div>
          <div class="field-value">{{ activity.description }}</div>
        </div>
      </el-card>
      <div class="text-2xl mb-4">Acteurs</div>
      <el-card class="mb-10">
        <div class="field">
          <div class="field-label">Responsabilité métier</div>
          <div class="field-value">{{ activity.responsabilite_metier }}</div>
        </div>
        <div class="field">
          <div class="field-label">Responsabilité SI</div>
          <div class="field-value">{{ activity.responsabilite_si }}</div>
        </div>
        <div class="field">
          <div class="field-label">Contributeur SI</div>
          <div class="field-value">{{ activity.contributeur_si }}</div>
        </div>
        <div class="field">
          <div class="field-label">Contributeurs métier</div>
          <div class="field-value">{{ activity.contributeur_metier }}</div>
        </div>
        <div class="field">
          <div class="field-label">AMOA</div>
          <div class="field-value">{{ activity.amoa }}</div>
        </div>
        <div class="field">
          <div class="field-label">AMOE</div>
          <div class="field-value">{{ activity.amoe }}</div>
        </div>
      </el-card>
      <div class="text-2xl mb-4">Qualifications</div>
      <el-card class="mb-10">
        <div class="field">
          <div class="field-label">Enjeu métier</div>
          <div class="field-value">{{ activity.enjeu_metier }}</div>
        </div>
        <div class="field">
          <div class="field-label">Niveau d'enjeu</div>
          <div class="field-value">{{ activity.niveau_enjeu }}</div>
        </div>
        <div class="field">
          <div class="field-label">Niveau de faisabilité</div>
          <div class="field-value">{{ activity.niveau_faisabilite }}</div>
        </div>
        <div class="field">
          <div class="field-label">Risques à ne pas faire</div>
          <div class="field-value">{{ activity.risque_a_ne_pas_faire }}</div>
        </div>
      </el-card>

      <div class="flex items-center justify-between mb-5">
        <div>
          <div class="text-2xl mr-3">Budgets</div>
          <div class="text-xl text-gray-600"> {{ activity.total_budgets | formatNumber  }}€</div>
        </div>
        <div class="actions">
          <router-link
            :to="{ name: 'activity.budget.create', params: { id: activity.id } , query: { type: 'Budget' }}"
          >
            <el-button type="primary" plain>
              <i class="el-icon-plus mr-1 font-bold"></i> Ajouter un budget
            </el-button>
          </router-link>
        </div>
      </div>
      <budget-items-table
        class="mb-10"
        :items="activity.budgets.filter(item => item.type == 'Budget')"
        @updated="handleUpdated"
      ></budget-items-table>

      <div class="flex items-center justify-between mb-5">
        <div>
          <div class="text-2xl mr-3">Charges internes AMOA</div>
          <div class="text-xl text-gray-600"> {{ activity.total_charges_amoa | formatNumber }}JH</div>
        </div>
        <div class="actions">
          <router-link
            :to="{ name: 'activity.budget.create', params: { id: activity.id } , query: { type: 'Charge interne AMOA' }}"
          >
            <el-button type="primary" plain>
              <i class="el-icon-plus mr-1 font-bold"></i> Ajouter une charge AMOA
            </el-button>
          </router-link>
        </div>
      </div>
      <budget-items-table
        class="mb-10"
        suffix="JH"
        :items="activity.budgets.filter(item => item.type == 'Charge interne AMOA')"
        @updated="handleUpdated"
      ></budget-items-table>

      <div class="flex items-center justify-between mb-5">
        <div>
          <div class="text-2xl mr-3">Charges internes MOE</div>
          <div class="text-xl text-gray-600"> {{ activity.total_charges_moe | formatNumber  }}JH</div>
        </div>
        <div class="actions">
          <router-link
            :to="{ name: 'activity.budget.create', params: { id: activity.id } , query: { type: 'Charge interne MOE' }}"
          >
            <el-button type="primary" plain>
              <i class="el-icon-plus mr-1 font-bold"></i> Ajouter une charge MOE
            </el-button>
          </router-link>
        </div>
      </div>
      <budget-items-table
        class="mb-10"
        suffix="JH"
        :items="activity.budgets.filter(item => item.type == 'Charge interne MOE')"
        @updated="handleUpdated"
      ></budget-items-table>
    </div>
  </div>
</template>



<script type="text/babel">
import { fetchActivity } from "@/api/activity";
import BudgetItemsTable from "@/components/BudgetItemsTable.vue";
import ActivityStatusTag from "@/components/ActivityStatusTag.vue";

export default {
  components: {
    BudgetItemsTable,
    ActivityStatusTag
  },
  data() {
    return {
      loading: false,
      activity: {}
    };
  },
  created() {
    this.loading = true;
    fetchActivity(this.$route.params.id).then(res => {
      this.activity = res.data;
      this.loading = false;
    });
  },
  methods: {
    handleUpdated(activity) {
      this.activity = activity
    }
  }
};
</script>
