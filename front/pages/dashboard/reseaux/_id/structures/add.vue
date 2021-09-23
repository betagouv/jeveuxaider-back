<template>
  <div>
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ reseau.name }}
        </div>
        <div class="mb-12 font-bold text-[1.75rem] text-[#242526]">
          Ajouter des organisations
        </div>
      </div>
      <div>
        <nuxt-link :to="`/dashboard/reseaux/${$route.params.id}`">
          <el-button>Retour</el-button>
        </nuxt-link>
      </div>
    </div>
    <div class="px-12 max-w-2xl">
      <div class="mb-2 text-gray-900 text-sm">Rechercher une organisation</div>
      <AutocompleteStructureSingle
        placeholder="Nom de l'organisation"
        @change="onAutocompleteChange"
      />
      <div
        v-if="organisations.length > 0"
        class="my-4 divide-y divide-gray-200"
      >
        <div
          v-for="organisation in organisations"
          :key="organisation.id"
          class="py-2 text-gray-900 text-sm flex justify-between"
        >
          <div>
            {{ organisation.name }}
          </div>
          <div
            class="text-red-400 cursor-pointer"
            @click="removeOrganisation(organisation.id)"
          >
            Retirer
          </div>
        </div>
      </div>

      <div class="flex pt-4">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Ajouter
          <span v-if="organisations.length > 0">
            {{ organisations.length }} organisations au réseau
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
  async asyncData({ $api, params }) {
    const reseau = await $api.getReseau(params.id)
    return {
      reseau,
    }
  },
  data() {
    return {
      loading: false,
      organisations: [],
    }
  },
  methods: {
    onAutocompleteChange(organisation) {
      this.organisations.push(organisation)
    },
    removeOrganisation(id) {
      this.organisations = this.organisations.filter((orga) => {
        return orga.id !== id
      })
    },
    onSubmit() {
      if (this.organisations.length == 0) {
        return
      }
      this.loading = true
      this.$api
        .addReseauOrga(
          this.reseau.id,
          this.organisations.map((orga) => orga.id)
        )
        .then((res) => {
          this.loading = false
          console.log('res', res.data)
          this.$router.push(`/dashboard/reseaux/${this.reseau.id}/structures`)
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>
