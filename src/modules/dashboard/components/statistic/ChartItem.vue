<template>
  <EcBox variant="card-2" class="mb-4 mr-3 w-full md:w-6/12 lg:inline-flex lg:flex-grow lg:w-auto" style="min-width: 12rem">
    <component :is="chartComponent" :chartData="chartInfo?.data" :options="chartOptions" />
  </EcBox>
</template>

<script>
import EcSpinner from "@/components/EcSpinner/index.vue"
import { BarChart, DoughnutChart, LineChart, PieChart, RadarChart, BubbleChart, ScatterChart, PolarAreaChart } from "vue-chart-3"
import { Chart, registerables } from "chart.js"

Chart.register(...registerables)

export default {
  name: "ChartItem",
  inject: ["isLoading"],
  props: {
    chartInfo: {
      type: Object,
      default: () => {},
    },
  },

  data() {
    return {}
  },

  setup() {},
  computed: {
    chartComponent() {
      switch (this.chartInfo?.type) {
        case "bar":
          return BarChart
        case "donut":
          return DoughnutChart
        case "line":
          return LineChart
        case "radar":
          return RadarChart
        case "bubble":
          return BubbleChart
        case "scatter":
          return ScatterChart
        case "polar":
          return PolarAreaChart
        default:
          return PieChart
      }
    },

    chartOptions() {
      return {
        responsive: true,
        plugins: {
          legend: {
            position: this.chartInfo?.title ?? "top",
          },
          title: {
            display: true,
            text: this.chartInfo?.title,
          },
        },
      }
    },
  },
  methods: {
    handleClickAdd() {
      // Go to add new page
      this.$router.push({
        name: this.info?.navigation?.name,
      })
    },
  },
  components: { EcSpinner, BarChart, DoughnutChart, LineChart, PieChart, RadarChart, BubbleChart, ScatterChart, PolarAreaChart },
}
</script>
