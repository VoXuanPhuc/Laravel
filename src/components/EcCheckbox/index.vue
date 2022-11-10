<template>
  <div :class="variantCls.root">
    <!-- Hidden input -->
    <input
      :id="id"
      v-model="checked"
      tabindex="-1"
      :class="variantCls.hiddenInput"
      type="checkbox"
      :disabled="disabled"
      @focus="onFocus"
    />

    <!-- Checkbox -->
    <div
      ref="div"
      :tabindex="disabled ? '-1' : '0'"
      :class="checkboxCls"
      @keydown.space="onSpacebarPress"
      @click="toggleCheckbox"
    >
      <slot v-if="checked">
        <i :class="assets.cls" v-html="assets.iconChecked" />
      </slot>
      <i :class="assets.cls" v-if="indeterminate && !checked" v-html="assets.iconIndeterminate" />
    </div>
  </div>
</template>

<script>
export default {
  name: "EcCheckbox",
  emits: ["update:modelValue"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    id: {
      type: String,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    indeterminate: {
      type: Boolean,
      default: false,
    },
    modelValue: {
      type: [Boolean, String, Array],
    },
    customValue: {
      type: [Boolean, String, Array],
    },
  },

  data() {
    return {
      checked: false,
    }
  },

  computed: {
    checkboxCls() {
      if (this.checked) return this.variantCls?.checkboxChecked
      else return this.variantCls?.checkbox
    },
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcCheckbox",
          variant: this.variant,
        })?.el ?? {}
      )
    },
    assets() {
      return (
        this.getComponentVariants({
          componentName: "EcCheckbox",
          variant: this.variant,
        })?.assets ?? {}
      )
    },
    modelType() {
      if (typeof this.modelValue === "boolean") return "boolean"
      else if (Array.isArray(this.modelValue)) return "array"
      else return "stringOrNull"
    },
  },

  watch: {
    checked: {
      handler() {
        let emittedValue = null
        switch (this.modelType) {
          case "boolean":
            emittedValue = this.checked
            break
          case "array":
            if (this.checked && !this.modelValue.includes(this.customValue)) {
              emittedValue = [...this.modelValue, this.customValue]
            } else {
              emittedValue = this.modelValue.filter((i) => i !== this.customValue)
            }
            break
          case "stringOrNull":
            emittedValue = this.checked ? this.customValue : null
            break
        }
        this.$emit("update:modelValue", emittedValue)
      },
      deep: true,
    },
    modelValue: {
      handler() {
        switch (this.modelType) {
          case "boolean":
            this.checked = this.modelValue
            break
          case "array":
            this.checked = this.modelValue.includes(this.customValue)
            break
          case "stringOrNull":
            this.checked = this.modelValue === this.customValue
            break
        }
      },
      immediate: true,
      deep: true,
    },
  },

  methods: {
    toggleCheckbox() {
      if (this.disabled) return
      this.checked = !this.checked
    },
    onSpacebarPress(e) {
      this.toggleCheckbox()
      this.$emit("keydown", e)
      /** Space on keydown will trigger scroll. We don't want that behavior */

      e.preventDefault()
    },
    onFocus() {
      this.$refs.div.focus()
    },
  },
}
</script>
