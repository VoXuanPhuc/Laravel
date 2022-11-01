<template>
  <RLayout class="relative z-0">
    <RLayoutTwoCol :leftCls="leftCls" :rightCls="rightCls">
      <template #left>
        <DashboardSummary></DashboardSummary>
      </template>

      <template #right>
        <DashboardNotification></DashboardNotification>
      </template>
    </RLayoutTwoCol>

    <!-- Bg -->
    <EcFlex class="justify-end relative my-auto bottom-0 h-12" style="background-image: url('')"> </EcFlex>
  </RLayout>
</template>
<script>
import DashboardSummary from "../components/bright_dashboard/DashboardSummary.vue"
import DashboardNotification from "../components/bright_dashboard/DashboardNotification.vue"
import useBrightDashboard from "../stores/useBrightDashboard"

export default {
  name: "ViewBrightDashboard",
  components: { DashboardSummary, DashboardNotification },

  setup() {
    const brightDashboardStore = useBrightDashboard()

    return {
      brightDashboardStore,
    }
  },

  computed: {
    leftCls() {
      return this.brightDashboardStore.notiSidebarOpened ? "lg:w-8/12  mb-8" : "lg:w-11/12  mb-8"
    },

    rightCls() {
      return this.brightDashboardStore.notiSidebarOpened ? "lg:w-4/12 mb-8" : "lg:w-1/12 mb-8"
    },
  },
}
</script>
