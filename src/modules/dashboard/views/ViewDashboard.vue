<template>
  <RLayout :title="$t('dashboard.dashboard')">
    <!-- Statistics List -->
    <StatisticList :listData="statisticList" />
    <ChartList :listData="chartList" />
  </RLayout>
</template>

<script>
import StatisticList from "./../components/statistic/StatisticList.vue"
import ChartList from "../components/statistic/ChartList.vue"
import useDashboardStore from "../stores/useDashboard"
import useTenantDashboardStore from "../stores/useTenantDashboard"
import { storeToRefs } from "pinia"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewDashboard",
  components: { StatisticList, ChartList },
  data() {
    return {
      isLoading: {
        status: true,
      },
    }
  },
  setup() {
    const globalStore = useGlobalStore()
    const dashboardStore = useDashboardStore()
    const tenantDashboardStore = useTenantDashboardStore()

    const { statisticList, chartList } = globalStore.isLandlord ? storeToRefs(dashboardStore) : storeToRefs(tenantDashboardStore)

    return {
      globalStore,
      dashboardStore,
      tenantDashboardStore,
      statisticList,
      chartList,
    }
  },
  mounted() {
    this.fetchData()
  },

  provide() {
    return {
      isLoading: this.isLoading,
    }
  },

  methods: {
    async fetchData() {
      this.isLoading.status = true

      if (this.globalStore.isLandlord) {
        await this.dashboardStore.fetchSystemStatisticsData()
      } else {
        await this.tenantDashboardStore.fetchTenantStatisticsData()
      }

      this.isLoading.status = false
    },
  },
}
</script>
