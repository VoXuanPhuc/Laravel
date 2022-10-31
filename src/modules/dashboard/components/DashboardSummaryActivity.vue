<template>
  <!-- Activities  -->
  <EcBox class="w-10/12 mt-8">
    <EcLabel class="font-semibold mt-6">List of Critical Business Activities</EcLabel>
    <EcFlex class="items-center">
      <!-- Activity Row -->
      <RFormInput iconPrefix="DActivityDoc" variant="primary-dashboard" componentName="EcInputText" field="form.name" />
    </EcFlex>

    <EcFlex class="items-center">
      <!-- Activity Row -->
      <RFormInput iconPrefix="DActivityDoc" variant="primary-dashboard" componentName="EcInputText" field="form.name" />
    </EcFlex>

    <EcFlex class="mt-6 justify-end hover:cursor-pointer text" @click="handleClickSeeAll">See All</EcFlex>
  </EcBox>
</template>

<script>
import { goto } from "@/modules/core/composables"

export default {
  name: "DashboardSummary",

  methods: {
    handleClickSeeAll() {
      goto("ViewActivityList")
    },
  },
}
</script>
