<template>
  <span :tooltip="tooltipText" :position="position"><slot /></span>
</template>

<script>
export default {
  name: "EcTooltip",

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
