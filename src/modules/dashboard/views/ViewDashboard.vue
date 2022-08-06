<template>
  <RLayout :title="$t('dashboard.dashboard')">
    <!-- Statistics List -->
    <StatisticList :listData="statisticList" />
  </RLayout>
</template>

<script>
import StatisticList from "./../components/StatisticList.vue"
import useDashboardStore from "../stores/useDashboard"
import { storeToRefs } from "pinia"

export default {
  name: "ViewDashboard",
  components: { StatisticList },
  setup() {
    const dashboardStore = useDashboardStore()

    const { statisticList } = storeToRefs(dashboardStore)

    return {
      dashboardStore,
      statisticList,
    }
  },
  created() {
    this.dashboardStore.fillData()
  },
}
</script>
