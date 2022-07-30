<template>
  <RLayout :title="$t('dashboard.dashboard')">
    <!-- Statistics List -->
    <StatisticList :listData="statisticList" />
  </RLayout>
</template>

<script>
import StatisticList from "./../components/StatisticList.vue"
import { useDashboard } from "./../use/useDashboard"

export default {
  name: "ViewDashboard",
  components: { StatisticList },
  setup() {
    const { state, send, statisticList } = useDashboard()
    return {
      state,
      send,
      statisticList,
    }
  },
}
</script>
