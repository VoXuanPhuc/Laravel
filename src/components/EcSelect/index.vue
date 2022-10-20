<template>
  <div :class="variantCls.root">
    <select
      :class="variantCls.select"
      :disabled="disabled"
      @change="emit('update:modelValue', $event.target.value)"
      @focus="emit('focus')"
      @blur="emit('blur')"
    >
      <option v-if="allowSelectNothing" value="">{{ placeholder }}</option>
      <option v-else-if="!iOS" :value="null" disabled selected hidden>
        {{ placeholder }}
      </option>
      <option
        v-for="(option, i) in options"
        :key="i"
        :selected="option[valueKey] === modelValue"
        :value="option[valueKey]"
        :disabled="option.disabled"
        :class="option.disabled ? variantCls.disabledOption : ''"
      >
        {{ option[nameKey] }}
      </option>
    </select>
    <div :class="variantCls.iconRoot">
      <slot name="icon" :variantCls="variantCls">
        <EcIcon :class="variantCls.icon" :icon="variantAssets.iconName" />
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "EcSelect",
  emits: ["update:modelValue", "blur", "focus"],
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
    placeholder: {
      type: String,
      default: "----",
    },
    allowSelectNothing: {
      type: Boolean,
      required: false,
      default: true,
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
    variantAssets() {
      return (
        this.getComponentVariants({
          componentName: "EcSelect",
          variant: this.variant,
        })?.assets ?? {}
      )
    },
    iOS() {
      return (
        ["iPad Simulator", "iPhone Simulator", "iPod Simulator", "iPad", "iPhone", "iPod"].includes(navigator.platform) ||
        // iPad on iOS 13 detection
        navigator.userAgent.includes("Mac")
      )
    },
  },
  methods: {
    emit(...args) {
      if (!this.disabled) {
        this.$emit(...args)
      }
    },
  },
}
</script>
