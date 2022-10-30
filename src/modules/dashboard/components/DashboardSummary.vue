<template>
  <!-- Title -->
  <EcLabel>Thursday, Augst 3rd</EcLabel>
  <EcLabel class="text-xl font-semibold">Good Morning, Cathy Client.</EcLabel>

  <!-- BIA  -->
  <EcBox class="w-8/12 mt-8">
    <EcFlex class="items-center">
      <!-- BIA Row -->
      <RFormInput variant="primary-lg" componentName="EcInputText" field="form.name" />
      <EcButton class="w-5/12 ml-3 rounded-xl bg-c3-200 text-cBlack border-0">In Progress</EcButton>
    </EcFlex>

    <EcFlex class="items-center mt-2">
      <!-- BIA Row -->
      <RFormInput variant="primary-lg" componentName="EcInputText" field="form.name" />
      <EcButton class="w-5/12 ml-3 rounded-xl bg-c3-200 text-cBlack border-0">In Progress</EcButton>
    </EcFlex>
  </EcBox>

  <!-- BCP  -->
  <EcBox class="w-8/12 mt-8">
    <EcLabel class="font-semibold mt-6">Business Continuity Plan (BCP) testing</EcLabel>
    <EcFlex class="items-center">
      <!-- BIA Row -->
      <RFormInput variant="primary-lg" componentName="EcInputText" field="form.name" />
      <EcButton class="w-5/12 ml-3 rounded-xl bg-c3-500">Up to date</EcButton>
    </EcFlex>

    <EcFlex class="items-center mt-2">
      <!-- BIA Row -->
      <RFormInput variant="primary-lg" componentName="EcInputText" field="form.name" />
      <EcButton class="w-5/12 ml-3 rounded-xl">March 2023</EcButton>
    </EcFlex>
  </EcBox>

  <!-- Activities  -->
  <EcBox class="w-10/12 mt-8">
    <EcLabel class="font-semibold mt-6">List of Critical Business Activities</EcLabel>
    <EcFlex class="items-center">
      <!-- BIA Row -->
      <RFormInput iconPrefix="DActivityDoc" variant="primary-lg" componentName="EcInputText" field="form.name" />
    </EcFlex>

    <EcFlex class="items-center">
      <!-- BIA Row -->
      <RFormInput iconPrefix="DActivityDoc" variant="primary-lg" componentName="EcInputText" field="form.name" />
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
