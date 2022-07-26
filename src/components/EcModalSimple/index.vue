<template>
  <div>
    <!-- Overlay -->
    <transition
      :enterFromClass="variantCls.overlayEnter"
      :enterActiveClass="variantCls.overlayEnterActive"
      :leaveActiveClass="variantCls.overlayLeaveActive"
      :leaveToClass="variantCls.overlayLeaveTo"
    >
      <div v-if="isVisible" :class="variantCls.overlay" :style="modalZIndex" />
    </transition>

    <!-- Modal -->
    <transition
      :enterFromClass="variantCls.enter"
      :enterActiveClass="variantCls.enterActive"
      :leaveActiveClass="variantCls.leaveActive"
      :leaveToClass="variantCls.leaveTo"
    >
      <JFlex
        v-if="isVisible"
        :class="variantCls.modalWrapper"
        @click.self="handleClickOverlay"
        :style="modalZIndex"
        v-scroll-lock="true"
      >
        <div :class="variantCls.root">
          <slot />
        </div>
      </JFlex>
    </transition>
  </div>
</template>

<script>
export default {
  name: "EcModalSimple",
  emits: ["overlay-click", "overlay:click"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    isVisible: {
      type: Boolean,
      default: false,
    },
    zIndex: {
      type: String,
      default: "",
    },
  },
  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcModalSimple",
          variant: this.variant,
        })?.el ?? {}
      )
    },
    modalZIndex() {
      return {
        zIndex: this.zIndex,
      }
    },
  },
  methods: {
    handleClickOverlay() {
      this.$emit("overlay-click")
      this.$emit("overlay:click")
    },
  },
}
</script>
