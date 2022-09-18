<template>
  <textarea
    :class="variantCls.root"
    :value="modelValue"
    :rows="rows"
    v-bind="computedListeners"
    v-on="{
      input: (event) => $emit('update:modelValue', event.target.value),
    }"
  />
</template>

<script>
export default {
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
    rows: {
      required: false,
      default: 10,
      type: Number,
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
          componentName: "EcInputLongText",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
}
</script>
