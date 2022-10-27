<template>
  <Carousel :settings="settings" :breakpoints="breakpoints">
    <template #sliders>
      <slot name=""></slot>
    </template>
    <template #addons>
      <Navigation />
    </template>
  </Carousel>
</template>

<script>
import "vue3-carousel/dist/carousel.css"
import { Carousel, Navigation } from "vue3-carousel"

export default {
  inheritAttrs: false,
  name: "EcCarosel",

  data() {
    return {
      // carousel settings
      settings: {
        itemsToShow: 1,
        snapAlign: "center",
      },
      // breakpoints are mobile first
      // any settings not specified will fallback to the carousel settings
      breakpoints: {
        // 700px and up
        700: {
          itemsToShow: 3.5,
          snapAlign: "center",
        },
        // 1024 and up
        1024: {
          itemsToShow: 5,
          snapAlign: "start",
        },
      },
    }
  },
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

  components: { Carousel, Navigation },
}
</script>
