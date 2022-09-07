<template>
  <div class="relative" :class="variantCls.root" style="min-height: 2.5rem">
    <!-- Color Label -->
    <div :class="variantCls.input">
      {{ computedLabel }}
    </div>
    <input type="color" :value="modelValue" @input="onUpdateModelValue" />
  </div>
</template>

<script>
export default {
  name: "EcColorPicker",
  emits: ["update:modelValue"],
  props: {
    variant: {
      type: String,
      default: "default",
    },

    modelValue: {
      required: true,
      default: "#000000",
    },

    placeholder: {
      type: String,
      default: "",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    mode: {
      type: String,
      default: "single",
    },
  },
  data() {
    return {}
  },
  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcColorPicker",
          variant: this.variant,
        })?.el ?? {}
      )
    },

    computedLabel() {
      return this.modelValue
    },
  },
  methods: {
    onUpdateModelValue(event) {
      if (this.disabled) return

      this.$emit("update:modelValue", event.target.value)
    },
  },
}
</script>
