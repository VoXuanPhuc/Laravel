<template>
  <div :class="variantCls.root">
    <div
      :class="modelValue ? variantCls.sliderTrue : variantCls.sliderFalse"
      :tabindex="disabled ? '-1' : '0'"
      @click="onInput"
      @keyup.enter="onInput"
      @focus="emit('focus')"
      @blur="emit('blur')"
    >
      <div
        :style="{
          transform: `translateX(${modelValue ? 100 : 0}%)`,
          transition: 'all 150ms ease',
        }"
        :class="modelValue ? variantCls.switchTrue : variantCls.switchFalse"
      />
    </div>
    <span v-if="showLabel" :class="modelValue ? variantCls.labelTrue : variantCls.labelFalse">
      {{ modelValue ? labelTrue : labelFalse }}
    </span>
  </div>
</template>

<script>
export default {
  name: "EcToggle",
  emits: ["update:modelValue", "blur", "focus"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    modelValue: {
      validator(value) {
        return value === null || value === true || value === false
      },
      reguired: true,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    showLabel: {
      type: Boolean,
      defafult: false,
    },
    labelTrue: {
      type: String,
      default: "Yes",
    },
    labelFalse: {
      type: String,
      default: "No",
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcToggle",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },

  methods: {
    emit(...args) {
      if (!this.disabled) {
        this.$emit(...args)
      }
    },
    onInput() {
      if (this.disabled) return
      this.$emit("update:modelValue", !this.modelValue)
    },
  },
}
</script>
