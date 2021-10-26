<template>
  <div>
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ structure.name }}
        </div>
        <div class="font-bold text-[1.75rem] text-[#242526]">
          Relier la structure à des réseaux
        </div>
      </div>
      <nuxt-link :to="`/dashboard/structure/${$route.params.id}`">
        <el-button>Retour</el-button>
      </nuxt-link>
    </div>
    <el-divider />

    <div class="px-12 max-w-2xl">
      <div class="mb-2 text-gray-900 text-sm">Rechercher un réseau</div>
      <AutocompleteReseauSingle
        placeholder="Nom du réseau"
        @change="onAutocompleteChange"
      />
      <div v-if="reseaux.length > 0" class="my-4 divide-y divide-gray-200">
        <div
          v-for="reseau in reseaux"
          :key="reseau.id"
          class="py-2 text-gray-900 text-sm flex justify-between"
        >
          <div>
            {{ reseau.name }}
            <span class="text-gray-400">#{{ reseau.id }}</span>
          </div>
          <div
            class="text-red-400 cursor-pointer"
            @click="removeReseau(reseau.id)"
          >
            Retirer
          </div>
        </div>
      </div>

      <div class="flex pt-4">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Ajouter
          <span v-if="reseaux.length > 0">
            {{ reseaux.length }} réseau(x) à l'organisation
          </span>
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  layout: 'dashboard',
  async asyncData({ $api, store, error, params }) {
    if (!['admin', 'referent'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const structure = await $api.getStructure(params.id)
    return {
      structure,
    }
  },
  data() {
    return {
      loading: false,
      reseaux: [],
    }
  },
  methods: {
    onAutocompleteChange(reseau) {
      this.reseaux.push(reseau)
    },
    removeReseau(id) {
      this.reseaux = this.reseaux.filter((orga) => {
        return orga.id !== id
      })
    },
    onSubmit() {
      if (this.reseaux.length == 0) {
        return
      }
      this.loading = true
      this.$api
        .addOrganisationReseaux(
          this.structure.id,
          this.reseaux.map((reseau) => reseau.id)
        )
        .then((res) => {
          this.loading = false
          this.$router.push(`/dashboard/structure/${this.structure.id}`)
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>
