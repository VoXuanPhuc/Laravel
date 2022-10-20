<template>
  <span :position="position" :tooltip="tooltipText" class="tooltip top-0 left-0 w-full h-full"><slot /></span>
</template>

<script>
export default {
  name: "EcTooltip",
  inject: ["getComponentVariants"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    tooltipText: {
      type: String,
      default: "Tooltip content",
    },
    position: {
      validator(value) {
        return ["top", "right", "bottom", "left"].includes(value)
      },
      type: String,
      default: "top",
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcTooltip",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
  methods: {},
}
</script>

<style lang="scss">
@import "tooltip.scss";
</style>
