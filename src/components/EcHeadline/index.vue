<template>
  <component :is="as" :class="variantCls.root" v-bind="$attrs">
    <slot />
  </component>
</template>

<script>
export default {
  name: "EcHeadline",
  inheritAttrs: false,
  props: {
    variant: {
      type: String,
      default: "default",
    },

    as: {
      type: String,
      default: () => "h1",
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcHeadline",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
}
</script>
