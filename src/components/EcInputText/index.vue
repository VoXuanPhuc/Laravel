<template>
  <input
    type="text"
    :class="variantCls.root"
    :value="modelValue"
    :disabled="disabled"
    v-bind="computedListeners"
    v-on="{
      input: (event) => $emit('update:modelValue', event.target.value),
    }"
  />
</template>

<script>
export default {
  name: "EcInputText",
  emits: ["update:modelValue"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    modelValue: {
      required: true,
      default: "",
      validator(val) {
        return val === null || typeof val === "string"
      },
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    computedListeners() {
      return this.disabled ? {} : this.$attrs
    },
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcInputText",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
}
</script>
