<template>
  <label :class="variantCls.root" v-bind="$attrs">
    <slot />
  </label>
</template>

<script>
export default {
  props: {
    variant: {
      type: String,
      default: "default",
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcLabel",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
}
</script>
