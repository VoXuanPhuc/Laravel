<template>
  <input
    type="tel"
    :class="variantCls.root"
    :value="displayedValue"
    :disabled="disabled"
    v-bind="computedListeners"
    v-on="{
      ...computedListeners,
      input: onInput,
      mouseup: onMouseUp,
      keyup: onKeyUp,
      blur: onBlur,
      keydown: onKeyDown,
    }"
  />
</template>

<script>
export default {
  name: "EcInputNumber",
  emits: ["update:modelValue", "blur", "onmouseup", "onkeyup", "onkeydown"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    thousandSeparator: {
      type: String,
      default: ",",
    },
    decimalSeparator: {
      type: String,
      default: ".",
    },
    /** TODO: Handle prefix and surfix is not implemented yet */
    prefix: {
      type: String,
      default: "",
    },
    surfix: {
      type: String,
      default: "",
    },
    precision: {
      type: Number,
      default: 2,
    },
    emptyValue: {
      type: [null, Number],
      default: null,
    },
    modelValue: {
      required: true,
      default: "",
      validator(val) {
        return val === null || typeof val === "string" || typeof val === "number"
      },
    },
    type: {
      required: false,
      default: "integer",
      validator(val) {
        return val === null || ["integer", "float"].includes(val)
      },
    },
    // TODO: allow negative numbers
    allowNegative: {
      required: false,
      default: false,
      type: Boolean,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      internalValue: "",
      prevValue: "",
    }
  },

  computed: {
    computedListeners() {
      return this.disabled ? {} : this.$attrs
    },
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcInputNumber",
          variant: this.variant,
        })?.el ?? {}
      )
    },
    lastCharacter() {
      return this.internalValue[this.internalValue.length - 1]
    },
    decimalPart() {
      const decimalPart = this.internalValue.split(this.decimalSeparator)[1]
      if (!decimalPart) return ""
      return decimalPart.substr(0, this.precision)
    },
    degitPart() {
      return this.internalValue.split(this.decimalSeparator)[0]
    },
    computedPrefix() {
      return this.internalValue.length > 0 ? this.prefix : ""
    },
    computedSurfix() {
      return this.internalValue.length > 0 ? this.surfix : ""
    },
    displayedValue() {
      const formattedDigits = this.degitPart.toString().replace(/\B(?=(\d{3})+(?!\d))/g, this.thousandSeparator)
      if (!formattedDigits) return ""

      const separator = this.lastCharacter === this.decimalSeparator || this.decimalPart ? this.decimalSeparator : ""
      return `${formattedDigits}${separator}${this.decimalPart}`
    },
  },

  watch: {
    modelValue: {
      handler() {
        this.internalValue = this.modelValue ? this.modelValue.toString() : ""
        this.prevValue = this.displayedValue
      },
      immediate: true,
      deep: true,
    },
  },

  methods: {
    findFirstDiffPos(a, b) {
      return [a, b].sort((a, b) => b.length - a.length).reduce((a, b) => [...a].findIndex((c, i) => c !== b[i]))
    },
    calculateCursorPosition(event) {
      const inputValue = event.target.value
      const currentPosition = event.target.selectionStart
      this.isCursorAtTheEnd = currentPosition === inputValue.length

      if (this.isCursorAtTheEnd || this.rangeSelection || currentPosition === 0) {
        return currentPosition
      }

      const commaInPrevValue = (this.prevValue.match(/,/g) || []).length
      const commaInCurrentValue = (this.displayedValue.match(/,/g) || []).length
      return event.target.selectionStart + (commaInCurrentValue - commaInPrevValue)
    },
    // TODO: add more types - float, negative
    onInput(event) {
      if (this.disabled) return

      let value = event.target.value

      if (this.type === "integer") {
        // Remove all not allowed symbols
        value = value.replace(/[^\d]/gi, "")
        value = parseInt(value)
      } else if (this.type === "float") {
        // eslint-disable-next-line no-useless-escape
        const regex = new RegExp(`[^0-9\\${this.decimalSeparator}]+`, "gi")
        value = value.replace(regex, "")
      }

      this.internalValue = isNaN(value) ? "" : value.toString()

      const nextCursorPosition = this.calculateCursorPosition(event)
      this.prevValue = this.displayedValue

      value = this.type === "integer" ? parseInt(this.internalValue) : parseFloat(this.internalValue)

      value = isNaN(value) ? this.emptyValue : value

      this.$emit("update:modelValue", value)

      // NOTE: when value is the same, component won't update when $emit
      // and thus input value is not changed so we have to forceUpdate the component everytime
      this.$forceUpdate()
      this.$nextTick(() => {
        if (!this.isCursorAtTheEnd) {
          event.target.setSelectionRange(nextCursorPosition, nextCursorPosition)
        }
      })
    },
    onBlur() {
      if (this.disabled) return
      if (this.lastCharacter === this.decimalSeparator) {
        const valueExceptLastCharacter = this.internalValue.slice(0, -1)
        this.internalValue = valueExceptLastCharacter
      }
      if (this.type === "integer" && !isNaN(parseInt(this.internalValue)) && parseInt(this.internalValue) === 0) {
        this.internalValue = "0"
      }
      this.$emit("blur", this.modelValue)
    },
    moveCursorToNumber(event, gap) {
      const value = event.target.value
      const cursorPos = event.target.selectionStart
      if (value[cursorPos - 1] === this.thousandSeparator && event.target.selectionStart === event.target.selectionEnd) {
        event.target.setSelectionRange(cursorPos + gap, cursorPos + gap)
      }
    },
    onMouseUp(event) {
      if (this.disabled) return
      this.moveCursorToNumber(event, -1)
      this.$emit("onmouseup", event)
    },
    onKeyUp(event) {
      if (this.disabled) return
      const LEFT_ARROW_KEY_CODE = 37
      const RIGHT_ARROW_KEY_CODE = 39
      if (event.keyCode === RIGHT_ARROW_KEY_CODE || event.keyCode === LEFT_ARROW_KEY_CODE) {
        this.moveCursorToNumber(event, event.keyCode === LEFT_ARROW_KEY_CODE ? -1 : 1)
      }
      this.$emit("onkeyup", event)
    },
    onKeyDown(event) {
      if (this.disabled) return
      const selectionStart = event.target.selectionStart
      const selectionEnd = event.target.selectionEnd
      this.rangeSelection = selectionStart !== selectionEnd
      const input = event.key
      const alreadyHaveDecimalSeparator = this.internalValue.includes(this.decimalSeparator)
      if (
        (input === this.decimalSeparator && this.type === "integer") ||
        (input === this.decimalSeparator && alreadyHaveDecimalSeparator)
      ) {
        event.preventDefault()
      }
      this.$emit("onkeydown", event)
    },
  },
}
</script>
