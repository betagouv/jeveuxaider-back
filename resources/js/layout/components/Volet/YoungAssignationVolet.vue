<template>
  <Volet>
    <template v-slot:content="{ row }">
      <div class="text-xs text-gray-600 uppercase text-center mt-16 mb-12">
        {{ row.structure.name }}
      </div>
      <el-card shadow="hover" class="overflow-visible">
        <div slot="header" class="clearfix flex flex-col items-center">
          <div class="-mt-10">
            <el-avatar
              v-if="row.structure.logo"
              :src="`${row.structure.logo}`"
              class="w-10 rounded-full border"
            />
            <el-avatar v-else class="bg-primary">
              {{ row.structure.name[0] }}
            </el-avatar>
          </div>
          <div class="font-bold text-lg mb-3 flex">
            {{ row.name }}
          </div>
        </div>
        <div class="flex items-center justify-center mb-4">
          <el-tag
            v-if="row.department"
            type="warning"
            class="m-1 ml-0"
            size="small"
          >
            {{ row.department | fullDepartmentFromValue }}
          </el-tag>
          <el-tooltip
            v-if="row.structure.ceu"
            class="item"
            effect="dark"
            :content="row.structure.structure_publique_etat_type"
            placement="top"
          >
            <el-tag size="small" class="m-1 ml-0" type="info">CEU</el-tag>
          </el-tooltip>
        </div>
        <div class="text-xs text-gray-600 relative">
          <div v-if="row.format" class="mb-2 flex">
            <div class="card-label">Format</div>
            <div class="text-gray-900 flex-1">
              {{ row.format | labelFromValue("mission_formats") }}
            </div>
          </div>
          <div v-if="row.start_date" class="mb-2 flex">
            <div class="card-label">Début</div>
            <div class="text-gray-900 flex-1">
              {{ row.start_date | formatLong }}
            </div>
          </div>
          <div v-if="row.end_date" class="mb-2 flex">
            <div class="card-label">Fin</div>
            <div class="text-gray-900 flex-1">
              {{ row.end_date | formatLong }}
            </div>
          </div>
          <div v-if="row.full_address.length > 0" class="mb-2 flex">
            <div class="card-label">Adresse</div>
            <div class="text-gray-900 flex-1">{{ row.full_address }}</div>
          </div>
          <template v-if="row.tuteur">
            <div v-if="row.tuteur" class="mb-2 flex">
              <div class="card-label">Tuteur</div>
              <div class="text-gray-900 flex-1">
                {{ row.tuteur.first_name }} {{ row.tuteur.last_name }}
              </div>
            </div>
            <div v-if="row.tuteur.mobile" class="mb-2 flex">
              <div class="card-label">Portable</div>
              <div class="text-gray-900 flex-1">
                {{ row.tuteur.mobile }}
              </div>
            </div>
            <div v-if="row.tuteur.phone" class="mb-2 flex">
              <div class="card-label">Téléphone</div>
              <div class="text-gray-900 flex-1">
                {{ row.tuteur.phone }}
              </div>
            </div>
          </template>
          <div v-if="row.domaines.length > 0" class="mb-2 flex">
            <div class="card-label">Domaines</div>
            <div class="text-gray-900 flex-1">
              {{ row.domaines.join(", ") }}
            </div>
          </div>
          <div v-if="row.participations_max" class="mb-2 flex">
            <div class="card-label">Max.</div>
            <div class="text-gray-900 flex-1">
              {{ row.participations_max }}
            </div>
          </div>
          <div v-if="row.frequence" class="mb-2 flex">
            <div class="card-label">Fréquence</div>
            <div class="text-gray-900 flex-1">
              {{ row.frequence }}
            </div>
          </div>
          <div v-if="row.periodes.length > 0" class="mb-2 flex">
            <div class="card-label">Périodes</div>
            <div class="text-gray-900 flex-1">
              {{ row.periodes.join(", ") }}
            </div>
          </div>
          <div v-if="row.description" class="mb-2 flex">
            <div class="card-label">Détails</div>
            <div class="text-gray-900 flex-1">
              {{ row.description }}
            </div>
          </div>
          <div v-if="row.actions" class="mb-2 flex">
            <div class="card-label">Actions</div>
            <div class="text-gray-900 flex-1">
              {{ row.actions }}
            </div>
          </div>
          <div v-if="row.justifications" class="mb-2 flex">
            <div class="card-label">MIG</div>
            <div class="text-gray-900 flex-1">
              {{ row.justifications }}
            </div>
          </div>
          <div v-if="row.contraintes" class="mb-2 flex">
            <div class="card-label">Contraintes</div>
            <div class="text-gray-900 flex-1">
              {{ row.contraintes }}
            </div>
          </div>
          <div v-if="row.handicap" class="mb-2 flex">
            <div class="card-label">Handicap</div>
            <div class="text-gray-900 flex-1">
              {{ row.handicap }}
            </div>
          </div>
        </div>
      </el-card>

      <el-form ref="missionForm" :model="form" label-position="top">
        <div class="mb-6 mt-12 flex text-xl text-gray-800">
          Proposer cette mission
        </div>
        <item-description>
          Il reste {{ registrationsLeft }}
          {{
            registrationsLeft
              | pluralize(["place disponible", "places disponibles"])
          }}
        </item-description>
        <div class="flex pt-2">
          <button-assign-mission
            type="primary"
            size="normal"
            :young="young"
            :mission="row"
          />
        </div>
      </el-form>
    </template>
  </Volet>
</template>

<script>
import Volet from "@/layout/components/Volet";
import ButtonAssignMission from "@/components/ButtonAssignMission";
import VoletRow from "@/mixins/VoletRow";
import ItemDescription from "@/components/forms/ItemDescription";

export default {
  name: "YoungAssignationVolet",
  components: { Volet, ItemDescription, ButtonAssignMission },
  mixins: [VoletRow],
  props: {
    young: {
      type: Object,
      required: true
    }
  },
  computed: {
    registrationsLeft() {
      let places = this.row.participations_max - this.row.youngs_count;
      return places > 0 ? places : 0;
    }
  }
};
</script>
