<template>
  <RLayout class="relative z-0">
    <RLayoutTwoCol leftCls="lg:w-8/12 lg:pr-2 mb-8" rightCls="lg:w-4/12 lg:pr-4 mb-8">
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
import DashboardSummary from "../components/DashboardSummary.vue"
import DashboardNotification from "../components/DashboardNotification.vue"

export default {
  name: "ViewModernDashboard",
  components: { DashboardSummary, DashboardNotification },
}
</script>
