<template>
  <td :class="tableCellClass" :style="tableCellStyle">
    <slot />
  </td>
</template>

<script>
export default {
  name: "RTableCell",
  props: {
    isTruncate: {
      type: Boolean,
      default: true,
    },
    variant: {
      type: String,
      default: "default",
    },
  },
  computed: {
    tableCellClass() {
      return {
        "text-base whitespace-no-wrap h-12 align-middle max-w-xs w-auto bg-cTransparent pl-6": true,
        "absolute top-auto right-0 px-2": this.variant === "gradient",
        truncate: this.isTruncate,
      }
    },
    tableCellStyle() {
      return this.variant === "gradient" ? "background: linear-gradient(270deg, #FFFFFF 85%, rgba(255, 255, 255, 0) 100%)" : ""
    },
  },
}
</script>
