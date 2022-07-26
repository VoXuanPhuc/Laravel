<template>
  <div :class="variantCls.root">
    <div
      v-for="(option, i) in options"
      :key="i"
      :class="[compare(option, modelValue) ? variantCls.itemSelected : variantCls.item]"
      :tabindex="disabled ? '-1' : '0'"
      @click="onClick(option)"
      @keyup.enter="onClick(option)"
      @focus="emit('focus')"
      @blur="emit('blur')"
    >
      {{ option[nameKey] }}
    </div>
  </div>
</template>

<script>
export default {
  name: "EcRadios",
  emits: ["update:modelValue", "blur", "focus"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    modelValue: {
      type: null,
      required: true,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    options: {
      required: true,
      default: () => [],
      type: Array,
    },
    nameKey: {
      type: String,
      default: "name",
    },
    valueKey: {
      type: String,
      default: "value",
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcRadios",
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

    onClick(option) {
      if (this.disabled) return
      this.$emit("update:modelValue", option[this.valueKey])
    },

    compare(option, valueTwo) {
      if (option[this.valueKey] === valueTwo) return true
      else return false
    },
  },
}
</script>
