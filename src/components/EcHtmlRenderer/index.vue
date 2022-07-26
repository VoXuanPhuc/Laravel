<template>
  <div></div>
</template>

<script>
export default {
  name: "EcHtmlRenderer",
  props: {
    htmlContent: {
      type: String,
      default: "",
    },
    tag: {
      type: String,
      default: "div",
    },
  },
  render(h) {
    return h(this.tag, {
      domProps: {
        innerHTML: this.htmlContent,
      },
    })
  },
}
</script>
