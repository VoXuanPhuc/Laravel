<template>
  <EcBox class="w-full" :class="$attrs.class">
    <!-- Form control inner -->
    <EcBox class="rounded-lg text-left">
      <label :for="field" :class="labelClass" class="font-medium cursor-text block pointer-events-none text-md mb-2">
        <slot name="label">
          {{ label }}
        </slot>
      </label>
      <!-- Input Wrapper -->
      <EcBox class="relative">
        <!-- Icon Prefix -->
        <slot name="prefix">
          <EcBox
            v-if="iconPrefix"
            :class="isFocus ? 'text-c1-500' : 'text-c0-300'"
            class="absolute flex items-center pointer-events-none py-2 px-5 left-0"
            style="top: 50%; transform: translateY(-50%)"
          >
            <EcIcon width="24" height="24" :icon="iconPrefix" />
          </EcBox>
        </slot>
        <!-- Component for input -->
        <component
          :is="componentName"
          :id="field"
          ref="control"
          :class="componentInputClass"
          :placeholder="placeholder"
          :modelValue="modelValue"
          :options="options"
          :disabled="disabled"
          v-bind="filteredAttrs"
          @focus="handleFocus"
          @blur="handleBlur"
        />
        <!-- Icon Suffix -->
        <slot name="suffix">
          <EcBox
            v-if="iconSuffix"
            :class="isFocus ? 'text-c0-500' : 'text-c0-300'"
            class="absolute flex items-center pointer-events-none py-2 px-3 right-0"
            style="top: 50%; transform: translateY(-50%)"
          >
            <EcIcon width="24" height="24" :icon="iconSuffix" />
          </EcBox>
        </slot>
      </EcBox>
    </EcBox>

    <!-- Error message -->
    <EcBox v-if="isDirty && hasError" class="mt-2">
      <EcBox>
        <!-- Goes through messages and if message exists in validation definition, it will display message based on if it's valid or not -->
        <EcText v-for="key in Object.keys(messages)" :key="key" class="text-cError-600 text-sm mt-1">
          {{ getMessage(getField, key) }}
        </EcText>
      </EcBox>
    </EcBox>
  </EcBox>
</template>

<script>
import get from "lodash/get"

export default {
  inheritAttrs: false,
  name: "RFormInput",
  props: {
    /**
     * @description Name of the component to render
     * @example EcInputText
     * @supportComponents
     *     EcInputText
     *     EcInputNumber
     *     EcInputLongText
     *     EcSelect
     *     EcMultiSelect
     *     EcRadios
     *     EcToggle
     *     EcDatePicker
     */
    componentName: {
      type: String,
      required: true,
      default: "input",
    },
    /**
     * @description Controller disable state
     */
    disabled: {
      type: Boolean,
      default: false,
    },
    /**
     * @description Dot notation of the field we are supposed to process. This field is also used for id for label
     * @example "clients[1].name"
     */
    field: {
      type: null,
      default: null,
    },
    /**
     * @description Instance of vue-lidate from parent
     * Simply in parent, we refer to it as $v
     */
    validator: {
      type: Object,
      default: () => {},
    },
    /**
     * @description Controller label
     */
    label: {
      type: String,
      default: "",
    },
    description: {
      type: String,
      default: "",
    },
    /**
     * @description Controller options
     */
    options: {
      type: Array,
      default: () => [],
    },
    /**
     * @description Controller value
     */
    modelValue: {
      required: false,
    },
    allowSelectNothing: {
      type: Boolean,
      required: false,
      default: true,
    },
    placeholder: {
      type: String,
      default: "",
    },
    iconPrefix: {
      type: String,
      default: "",
    },
    iconSuffix: {
      type: String,
      default: "",
    },
    /**
     * @description Component theme (mostly for label color)
     */
    dark: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isFocus: false,
      messageKeys: [
        "required",
        "requiredIf",
        "email",
        "age",
        "hkid",
        "alpha",
        "alphaNum",
        "maxLength",
        "minLength",
        "sameAs",
        "noNumber",
        "phone",
      ],
    }
  },
  computed: {
    filteredAttrs() {
      const newAttrs = { ...this.$props, ...this.$attrs }
      delete newAttrs.class
      return newAttrs
    },
    messages() {
      const result = {}
      this.messageKeys.forEach((key) => {
        result[key] = this.$t(`core.${key}`)
      })
      return result
    },
    labelClass() {
      return [this.dark ? "text-cWhite" : "text-cBlack"]
    },
    /**
     * Based on field address it will return field from validator object
     */
    getField() {
      return get(this.validator, this.field)
    },
    /**
     * Check if the field was already touched by user
     */
    isDirty() {
      if (Array.isArray(this.field)) {
        return get(this.validator, [...this.field, "$dirty"])
      }
      return get(this.validator, `${this.field}.$dirty`)
    },
    /**
     * Check if the field has errors
     */
    hasError() {
      if (Array.isArray(this.field)) {
        return get(this.validator, [...this.field, "$error"])
      }
      return get(this.validator, `${this.field}.$error`)
    },
    componentInputClass() {
      return this.iconPrefix ? "pl-16" : this.iconSuffix ? "pr-10" : ""
    },
  },
  methods: {
    getMessage(fieldObject = {}, key = "") {
      const found = Object.keys(fieldObject).find((item) => item === key)
      if (found) {
        return fieldObject[key].$invalid ? this.interpolate(this.messages[key], fieldObject, key) : null
      } else return ""
    },

    /**
     * String interpolation when we replace {0} with a specific parameter
     */
    interpolate(str, fieldObject, key) {
      // Go to $params[key] and get possible values that will be added to the str
      const values = fieldObject[key].$message

      // Some validators don't have values, in that case return str
      if (!values) return str

      // Find all {} to replace
      const reg = /({.*?})/gi
      const toReplace = str.match(reg)
      if (!toReplace) return str

      // Replace all {} with values
      let output = str

      // function to allow retrieving nested values
      function findValueFromKey(key, values) {
        return key
          .replace(/{|}/g, "")
          .split(".")
          .reduce((acc, cur) => acc[cur], values)
      }
      toReplace.forEach((item) => {
        // const valueKey = item.replace("{", "").replace("}", ""); // not needed with fn
        output = output.replace(item, findValueFromKey(item, values)) // replaces {count} with passed parameter e.g. 2
      })

      return output
    },
    handleFocus() {
      this.isFocus = true
    },
    handleBlur() {
      this.isFocus = false
    },
  },
}
</script>
