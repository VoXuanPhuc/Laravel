<template>
  <!-- BCP  -->
  <EcBox class="w-8/12 mt-8">
    <EcLabel class="font-semibold mt-6">Business Continuity Plan (BCP) testing</EcLabel>
    <EcFlex class="items-center">
      <!-- BCP Row -->
      <EcInputText variant="primary-dashboard" componentName="EcInputText" field="form.name" />
      <EcButton class="w-5/12 ml-3 rounded-lg bg-c3-500">Up to date</EcButton>
    </EcFlex>

    <EcFlex class="items-center mt-2">
      <!-- BCP Row -->
      <EcInputText variant="primary-dashboard" componentName="EcInputText" field="form.name" />
      <EcButton class="w-5/12 ml-3 rounded-lg">March 2023</EcButton>
    </EcFlex>
  </EcBox>
</template>

<script>
import { goto } from "@/modules/core/composables"

export default {
  name: "DashboardSummaryBCP",

  methods: {
    handleClickSeeAll() {
      goto("ViewActivityList")
    },
  },
}
</script>
