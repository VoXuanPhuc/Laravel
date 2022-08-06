<template>
  <EcFlex class="flex-col lg:flex-row mt-4 lg:mt-8">
    <!-- Left Column -->
    <EcBox class="lg:w-7/12 lg:pr-4 mb-8">
      <slot name="left" />
    </EcBox>
    <!-- Right Column -->
    <EcBox class="lg:w-5/12 lg:pl-4">
      <slot name="right" />
    </EcBox>
    <EcBox v-if="isLoading" v-scroll-lock="true" class="fixed z-50 top-0 left-0 w-full h-full bg-c0-900 bg-opacity-25">
      <EcFlex class="w-full h-full justify-center items-center">
        <EcSpinner variant="primary-lg" />
      </EcFlex>
    </EcBox>
  </EcFlex>
</template>

<script>
export default {
  name: "RLayoutTwoCol",
  props: {
    isLoading: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
