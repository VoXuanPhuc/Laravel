<template>
  <component :is="as" :class="variantCls.root" v-bind="$attrs">
    <slot />
  </component>
</template>

<script>
export default {
  inheritAttrs: false,
  name: "EcFlex",
  props: {
    variant: {
      type: String,
      default: "default",
    },

    as: {
      type: String,
      default: () => "div",
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcFlex",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
}
</script>
