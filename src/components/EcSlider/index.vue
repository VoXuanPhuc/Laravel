<template>
  <input
    type="range"
    :min="min"
    :max="max"
    :step="step"
    :value="value"
    :class="variantCls.root"
    :disabled="disabled"
    @change="onChange"
    @input="onInput"
    @focus="$emit('focus')"
    @blur="$emit('blur')"
  />
</template>

<script>
export default {
  props: {
    variant: {
      type: String,
      default: "default",
    },
    value: {
      type: Number,
    },
    min: {
      type: Number,
    },
    max: {
      type: Number,
    },
    step: {
      type: Number,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcSelect",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },

  methods: {
    onInput(e) {
      if (!e?.target?.value) return 0
      const valAsNumber = Number(e.target.value)
      this.$emit("input", valAsNumber)
    },
    /** IE don't understand onInput so we have to use onChange instead */
    onChange(e) {
      if (!e?.target?.value) return 0
      const valAsNumber = Number(e.target.value)
      this.$emit("input", valAsNumber)
    },
  },
}
</script>
