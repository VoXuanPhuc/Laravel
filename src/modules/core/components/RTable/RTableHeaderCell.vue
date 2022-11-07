<template>
  <td :class="headerCellClass" :style="headerCellStyle">
    <slot />
  </td>
</template>

<script>
export default {
  name: "RTableHeaderCell",
  props: {
    variant: {
      type: String,
      default: "default",
    },
  },
  computed: {
    headerCellClass() {
      return {
        "text-base text-c1-800 h-12 px-6 py-2 text-left whitespace-no-wrap font-semibold": true,
        "absolute top-0 right-0 px-8": this.variant === "gradient",
      }
    },
    headerCellStyle() {
      return this.variant === "gradient" ? "background: linear-gradient(270deg, #F6F7FB 85%, rgba(255, 255, 255, 0) 100%)" : ""
    },
  },
}
</script>
