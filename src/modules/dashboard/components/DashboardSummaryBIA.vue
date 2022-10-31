<template>
  <!-- BIA  -->
  <EcBox class="w-8/12 mt-8">
    <EcFlex class="items-center">
      <!-- BIA Row -->
      <EcInputText variant="primary-dashboard" componentName="EcInputText" field="form.name" />
      <EcButton class="w-5/12 ml-3 rounded-lg bg-c3-200 text-cBlack border-0">In Progress</EcButton>
    </EcFlex>

    <EcFlex class="items-center mt-2">
      <!-- BIA Row -->
      <EcInputText variant="primary-dashboard" componentName="EcInputText" field="form.name" />
      <EcButton class="w-5/12 ml-3 rounded-lg bg-c3-200 text-cBlack border-0">In Progress</EcButton>
    </EcFlex>
  </EcBox>
</template>

<script>
import { goto } from "@/modules/core/composables"

export default {
  name: "DashboardSummaryBIA",

  methods: {
    handleClickSeeAll() {
      goto("ViewActivityList")
    },
  },
}
</script>
