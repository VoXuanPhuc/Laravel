<template>
  <EcBox class="rounded-full w-full relative bg-cWarning-500 bg-opacity-25" style="height: 0.5rem">
    <EcBox class="absolute h-full left-0 top-0 bg-cWarning-500 rounded-full" :style="progressStyle"></EcBox>
  </EcBox>
</template>

<script>
export default {
  name: "RProgressBar",
  props: {
    percentage: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    progressStyle() {
      return `min-width: 0.5rem; width: ${this.percentage}%; transition: all .3s linear`
    },
  },
}
</script>
