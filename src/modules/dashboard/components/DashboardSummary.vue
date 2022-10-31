<template>
  <!-- Title -->
  <EcLabel class="text-c3-100">Thursday, Augst 3rd</EcLabel>
  <EcLabel class="text-xl font-semibold">Good Morning, Cathy Client.</EcLabel>

  <!-- BIA  -->
  <DashboardSummaryBIA />

  <!-- BCP  -->
  <DashboardSummaryBCP />

  <!-- Activities  -->
  <DashboardSummaryActivity />
</template>

<script>
import { goto } from "@/modules/core/composables"
import DashboardSummaryBIA from "./DashboardSummaryBIA"
import DashboardSummaryBCP from "./DashboardSummaryBCP"
import DashboardSummaryActivity from "./DashboardSummaryActivity"

export default {
  name: "DashboardSummary",

  components: { DashboardSummaryBIA, DashboardSummaryBCP, DashboardSummaryActivity },
  methods: {
    handleClickSeeAll() {
      goto("ViewActivityList")
    },
  },
}
</script>
