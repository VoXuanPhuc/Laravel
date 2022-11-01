<template>
  <!-- Title -->
  <EcBox>
    <EcLabel class="text-c3-100">{{ date }}</EcLabel>
    <EcLabel class="text-xl font-semibold">{{ greating }}, {{ userFullName }}.</EcLabel>
  </EcBox>

  <!-- BIA  -->
  <DashboardSummaryBIA />

  <!-- BCP  -->
  <DashboardSummaryBCP />

  <!-- Activities  -->
  <DashboardSummaryActivity />
</template>

<script>
import { goto } from "@/modules/core/composables"
import * as helper from "@/readybc/composables/helpers/helpers"
import { dateHelper } from "@/readybc/composables/helpers/dateHelper"

import DashboardSummaryBIA from "./DashboardSummaryBIA"
import DashboardSummaryBCP from "./DashboardSummaryBCP"
import DashboardSummaryActivity from "./DashboardSummaryActivity"

export default {
  name: "DashboardSummary",

  components: { DashboardSummaryBIA, DashboardSummaryBCP, DashboardSummaryActivity },

  setup() {},
  computed: {
    date() {
      return dateHelper.dashboardDate()
    },

    /**
     *
     */
    greating() {
      const today = new Date()
      const curentHr = today.getHours()

      if (curentHr < 12) {
        return "Good Morning"
      } else if (curentHr > 12 && curentHr < 18) {
        return "Good Afternoon"
      }
      // else if (curentHr > 18) {
      //   return "Good Evening"
      // }

      return "Welcome"
    },
    userFullName() {
      return helper.getUserFullName()
    },
  },
  methods: {
    handleClickSeeAll() {
      goto("ViewActivityList")
    },
  },
}
</script>
