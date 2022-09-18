<template>
  <component :is="as" :class="variantCls.root" v-bind="$attrs">
    <slot />
  </component>
</template>

<script>
export default {
  inheritAttrs: false,
  name: "EcText",
  props: {
    variant: {
      type: String,
      default: "default",
    },

    as: {
      type: String,
      default: () => "p",
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcText",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
}
</script>
