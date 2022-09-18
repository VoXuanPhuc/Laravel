<template>
  <div :class="variantCls.root">
    <!-- Button - -->
    <div
      :class="[parseFloat(computedValue) === min ? variantCls.buttonDisabled : variantCls.button]"
      :tabindex="disabled ? '-1' : '0'"
      @click="decrease"
      @focus="emit('focus')"
      @blur="emit('blur')"
      @keyup.enter="decrease"
    >
      -
    </div>

    <!-- Middle -->
    <input
      type="text"
      :class="variantCls.counter"
      :value="computedValue"
      v-on="{
        input: (event) => $emit('update:modelValue', event.target.value),
      }"
    />

    <!-- Button + -->
    <div
      :class="[parseFloat(computedValue) === max ? variantCls.buttonDisabled : variantCls.button]"
      :tabindex="disabled ? '-1' : '0'"
      @click="increase"
      @focus="emit('focus')"
      @blur="emit('blur')"
      @keyup.enter="increase"
    >
      +
    </div>
  </div>
</template>

<script>
export default {
  props: {
    variant: {
      type: String,
      default: "default",
    },
    modelValue: {
      required: true,
      default: "",
      validator(val) {
        return val === null || typeof val === "string" || typeof val === "number"
      },
    },
    min: {
      type: Number,
      required: true,
      default: 0,
    },
    max: {
      type: Number,
      required: true,
      default: 10,
    },
    step: {
      type: Number,
      required: true,
      default: 1,
    },
    decimalPlaces: {
      type: Number,
      required: false,
      default: 0,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    computedValue() {
      if (!this.modelValue) return parseFloat(this.min).toFixed(this.decimalPlaces)
      return parseFloat(this.modelValue).toFixed(this.decimalPlaces)
    },

    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcInputNumberStepper",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },

  emits: ["update:modelValue"],
  methods: {
    emit(...args) {
      if (!this.disabled) {
        this.$emit(...args)
      }
    },

    inputValue(value) {
      if (value < this.min) value = this.min

      this.$emit("update:modelValue", value)
    },

    decrease() {
      if (this.disabled) return

      let value = parseFloat(this.modelValue)
      value = isNaN(value) ? this.min : value
      value = value - this.step

      // If value is lower than min, show min
      if (value < this.min) value = this.min

      this.$emit("update:modelValue", parseFloat(value.toFixed(this.decimalPlaces)))
    },

    increase() {
      if (this.disabled) return

      let value = parseFloat(this.modelValue)
      value = isNaN(value) ? this.min : value
      value = value + this.step

      // If value is lower than max, show max
      if (value > this.max) value = this.max

      this.$emit("update:modelValue", parseFloat(value.toFixed(this.decimalPlaces)))
    },
  },
}
</script>
