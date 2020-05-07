<template>
  <div>
    <el-card shadow="never">
      <div class="bg-white p-4">
        <div class="flex mb-3">
          <div class="label mb-3 text-lg font-bold text-secondary uppercase flex-1">Nouvelles créations</div>
          <div class="actions">
            <el-input-number
              class="mr-3"
              size="small"
              v-model="year"
            ></el-input-number>
          </div>
        </div>
        <bar-chart
          :height="150"
          v-if="!loading"
          :chartData="chartData"
          :options="options"
          class="p-4"
        />
      </div>
    </el-card>
  </div>
</template>

<script type="text/babel">
import BarChart from "@/components/charts/BarChart";
import { chartCreated } from "@/api/app";


export default {
  components: { BarChart },
  props: {
    type: {
      type: String,
      required: true
    }
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
                  precision:0
              }
            }
          ],
          xAxes: [
            {
              display: true,
              gridLines: {
                display: false
              },
            }
          ]
        },
      }
    };
  },
  computed: {
    chartData() {
      return {
        labels: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Décembre'],
        datasets: [
          {
            label: 'Nouvelles créations',
            backgroundColor: "#00405C",
            data: this.datas.items
          }
        ]
      };
    }
  },
  created() {
    this.fetchDatas();
  },
  watch: {
    year: function(val) {
      this.fetchDatas();
    }
  },
  methods: {
    fetchDatas(params) {
      chartCreated({
        type: this.type,
        year: this.year
      }).then(res => {
        this.datas = res.data;
        this.loading = false;
      });
    }
  }
};
</script>
