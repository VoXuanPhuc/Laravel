<template>
  <div v-click-outside="hideCalendar" class="relative" :class="variantCls.root" style="min-height: 2rem">
    <!-- Date Label -->
    <div :class="variantCls.input" @click="toggleCalendar">
      {{ computedLabel }}
    </div>
    <transition
      :enterFromClass="variantCls.enterFrom"
      :enterActiveClass="variantCls.enterActive"
      :leaveActiveClass="variantCls.leaveActive"
      :leaveToClass="variantCls.leaveTo"
    >
      <EcCalendar
        v-if="calendarVisible"
        :modelValue="computedValue"
        :variant="componentInstanceVariants.calendar"
        :mode="mode"
        v-bind="filteredAttrs"
        @update:modelValue="onUpdateModelValue"
      />
    </transition>
  </div>
</template>

<script>
/**
 * @description This component can be used for picking one day
 * We are using https://vcalendar.io/
 * @param {ISOString | Date} value - it accepts String date or JavaScript Date object
 * @returns {ISOString} - it returns simplified ISOString like so "2019-01-24"
 */
import dayjs from "dayjs"

export default {
  name: "EcDatePicker",
  emits: ["update:modelValue"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    format: {
      type: String,
      default: "DD/MM/YYYY",
    },
    modelValue: {
      // Accepts {String in ISO date format YYYY-MM-DD}, {Null} or {Date}
      validator(val) {
        return val === null || typeof val === "string" || val instanceof Date || typeof val === "object"
      },
      required: true,
      default: null,
    },
    placeholder: {
      type: String,
      default: "",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    mode: {
      type: String,
      default: "single",
    },
  },
  data() {
    return {
      calendarVisible: false,
    }
  },
  computed: {
    filteredAttrs() {
      const newAttrs = { ...this.$props, ...this.$attrs }
      delete newAttrs.class
      return newAttrs
    },
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcDatePicker",
          variant: this.variant,
        })?.el ?? {}
      )
    },
    componentInstanceVariants() {
      return (
        this.getComponentVariants({
          componentName: "EcDatePicker",
          variant: this.variant,
        })?.componentInstanceVariants ?? {}
      )
    },
    computedValue() {
      if (!this.modelValue) return null
      // If value is string, convert it to date
      if (typeof this.modelValue === "string") return new Date(this.modelValue)
      return this.modelValue
    },
    computedLabel() {
      if (!this.modelValue || this.modelValue.length === 0) {
        return this.placeholder
      } else if (this.mode === "multiple") {
        return this.modelValue
          .map((d) => {
            return dayjs(d).format(this.format)
          })
          .join(", ")
      } else if (this.mode === "range") {
        return this.modelValue
          .map((d) => {
            return dayjs(d).format(this.format)
          })
          .join(" - ")
      }
      return dayjs(this.modelValue).format(this.format)
    },
  },
  methods: {
    onUpdateModelValue(value) {
      if (this.disabled) return
      if (this.mode === "single") {
        this.toggleCalendar()
      }
      this.$emit("update:modelValue", value)
    },
    toggleCalendar() {
      if (this.disabled) return
      this.calendarVisible = !this.calendarVisible
    },
    hideCalendar() {
      this.calendarVisible = false
    },
  },
}
</script>
