<template>
  <img :src="src" class="w-auto object-fit object-center" :class="[heightPercent]" ref="imgRef" />
</template>

<script>
export default {
  name: "EcImageViewer",
  props: {
    src: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      heightPercent: "",
    }
  },
  computed: {},

  methods: {
    autoScaleImageHeight() {
      if (this.$refs.imgRef?.height >= screen.height) {
        this.heightPercent = "h-[70%]"
      } else {
        this.heightPercent = ""
      }
    },
  },

  mounted() {
    this.autoScaleImageHeight()
  },
}
</script>
