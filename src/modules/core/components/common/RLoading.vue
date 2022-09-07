<template>
  <EcBox v-scroll-lock="true" class="fixed z-50 top-0 left-0 w-full h-full bg-c0-900 bg-opacity-25">
    <EcFlex class="w-full h-full justify-center items-center">
      <EcSpinner :type="type" :variant="variant" />
    </EcFlex>
  </EcBox>
</template>

<script>
export default {
  name: "RLoading",
  props: {
    variant: {
      type: String,
      default: "primary-lg",
    },
    type: {
      type: String,
      default: "circle",
    },
  },
}
</script>
