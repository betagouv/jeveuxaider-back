<template>
  <div>
    <el-card shadow="never">
      <div class="bg-white p-4">
        <div class="flex mb-3">
          <div
            class="label mb-3 text-lg font-bold text-secondary uppercase flex-1"
          >
            Nouvelles créations
          </div>
          <div class="actions">
            <el-input-number v-model="year" class="mr-3" size="small" />
          </div>
        </div>
        <ChartBar
          v-if="!loading"
          :height="150"
          :chart-data="chartData"
          :options="options"
          class="p-4"
        />
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: true,
      year: new Date().getFullYear(),
      datas: [],
      maxValue: 0,
      options: {
        responsive: true,
        maintainAspectRatio: true,
        legend: {
          display: false,
        },
        scales: {
          yAxes: [
            {
              display: true,
              ticks: {
                precision: 0,
              },
            },
          ],
          xAxes: [
            {
              display: true,
              gridLines: {
                display: false,
              },
            },
          ],
        },
      },
    }
  },
  computed: {
    chartData() {
      return {
        labels: [
          'Janv.',
          'Févr.',
          'Mars',
          'Avril',
          'Mai',
          'Juin',
          'Juill.',
          'Aout',
          'Sept.',
          'Oct.',
          'Nov.',
          'Déc.',
        ],
        datasets: [
          {
            label: 'Nouvelles créations',
            backgroundColor: '#2C5283',
            data: this.datas.items,
          },
        ],
      }
    },
  },
  watch: {
    year() {
      this.fetchDatas()
    },
  },
  created() {
    this.fetchDatas()
  },
  methods: {
    fetchDatas() {
      this.$api
        .chartCreated({
          type: this.type,
          year: this.year,
        })
        .then((res) => {
          this.datas = res.data
          this.loading = false
        })
    },
  },
}
</script>
