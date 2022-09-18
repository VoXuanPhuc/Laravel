<template>
  <div :class="variantCls.root">
    <div
      v-for="(option, i) in options"
      :key="i"
      :data-id="option.id"
      :class="[compare(option, modelValue) ? variantCls.itemSelected : variantCls.item]"
      :tabindex="disabled ? '-1' : '0'"
      @keypress.enter="onClick(option)"
      @click="onClick(option)"
      @focus="emit('focus')"
      @blur="emit('blur')"
    >
      {{ option[nameKey] }}
    </div>
  </div>
</template>

<script>
export default {
  name: "EcCheckboxGroup",
  emits: ["focus", "blur", "update:modelValue"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    modelValue: {
      required: true,
      default: () => [],
      validator(val) {
        return val === null || Array.isArray(val)
      },
    },
    options: {
      type: Array,
      required: true,
      default: () => [],
    },
    nameKey: {
      type: String,
      default: "name",
    },
    valueKey: {
      type: String,
      default: "value",
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
          componentName: "EcCheckboxGroup",
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

    compare(option) {
      if (!this.modelValue) return false
      if (this.modelValue.find((item) => item === option[this.valueKey])) return true
      else return false
    },

    onClick(option) {
      if (this.disabled) return
      const newValue = !this.modelValue ? [] : [...this.modelValue]

      if (newValue.find((item) => item === option[this.valueKey])) {
        newValue.splice(newValue.indexOf(option[this.valueKey]), 1)
      } else {
        newValue.push(option[this.valueKey])
      }

      // Return results
      this.$emit("update:modelValue", newValue)
    },
  },
}
</script>
