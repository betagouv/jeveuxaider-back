<template>
  <el-card
    shadow="never"
    class="mb-5 p-5 uppercase"
    :class="{ 'hover:border-[#1f0391] cursor-pointer': link }"
  >
    <div @click="onClick">
      <div class="label mb-3 text-lg font-bold text-secondary">
        {{ label }}
      </div>
      <template v-if="!$fetchState.pending">
        <div class="count text-primary font-medium text-2xl">
          {{ data.total | formatNumber }}
        </div>
        <div class="flex flex-wrap">
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Bénévoles</div>
            <div class>
              {{ data.volontaire | formatNumber }}
            </div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Responsables</div>
            <div class>
              {{ data.responsable | formatNumber }}
            </div>
          </div>
          <div class="mr-6 mt-6">
            <div class="text-gray-500 text-sm">Service civique</div>
            <div class>
              {{ data.service_civique | formatNumber }}
            </div>
          </div>
          <template
            v-if="
              $store.getters.contextRole == 'admin' ||
              $store.getters.contextRole == 'analyste'
            "
          >
            <div class="mr-6 mt-6">
              <div class="text-gray-500 text-sm">Départementaux</div>
              <div class>
                {{ data.referent | formatNumber }}
              </div>
            </div>
            <div class="mr-6 mt-6">
              <div class="text-gray-500 text-sm">Régionaux</div>
              <div class>
                {{ data.referent_regional | formatNumber }}
              </div>
            </div>
            <!-- <div class="mr-6 mt-6">
              <div class="text-gray-500 text-sm">Superviseurs</div>
              <div class>
                {{ data.superviseur | formatNumber }}
              </div>
            </div> -->
            <div class="mr-6 mt-6">
              <div class="text-gray-500 text-sm">Tetes de réseau</div>
              <div class>
                {{ data.tete_de_reseau | formatNumber }}
              </div>
            </div>
            <div class="mr-6 mt-6">
              <div class="text-gray-500 text-sm">Modérateurs</div>
              <div class>
                {{ data.admin | formatNumber }}
              </div>
            </div>
          </template>
        </div>
      </template>
      <template v-else>
        <i class="el-icon-loading" />
      </template>
    </div>
  </el-card>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    link: {
      type: String,
      required: false,
      default: null,
    },
  },
  data() {
    return {
      data: null,
    }
  },
  async fetch() {
    const statistics = await this.$api.statistics(this.name)
    this.data = statistics.data
  },
  fetchOnServer: false,
  methods: {
    onClick() {
      if (this.link && this.$store.getters.contextRole != 'analyste') {
        this.$router.push(this.link)
      }
    },
  },
}
</script>
