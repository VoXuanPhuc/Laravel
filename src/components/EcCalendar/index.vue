<template>
  <section :class="variantCls.calendar">
    <section :class="variantCls.navigation">
      <div :class="variantCls.changeMonthButtons" @click="decrementMonth(viewingMonth)">
        <slot name="previousMonth">
          <EcIcon width="20" height="20" icon="ChevronLeft" />
        </slot>
      </div>
      <select id="months" v-model="viewingMonth.month" :disabled="disabled" name="months" :class="variantCls.monthYearSelect">
        <option v-for="month of months" :key="month.label" :value="month.value">
          {{ month.label }}
        </option>
      </select>
      <select id="years" v-model="viewingMonth.year" :disabled="disabled" name="years" :class="variantCls.monthYearSelect">
        <option v-for="year of years" :key="year">{{ year }}</option>
      </select>
      <div :class="variantCls.changeMonthButtons" @click="incrementMonth(viewingMonth)">
        <slot name="nextMonth">
          <EcIcon width="20" height="20" icon="ChevronRight" />
        </slot>
      </div>
    </section>
    <section id="weekdays" :class="variantCls.weekdaysWrapper">
      <span v-for="weekday of weekdays" :key="weekday" :class="variantCls.weekday" style="flex-basis: calc(100% / 7)">
        {{ weekday }}
      </span>
    </section>
    <section id="dates" :class="variantCls.datesWrapper">
      <div v-for="date of monthView" :key="date.value" :class="variantCls.dateWrapper" style="flex-basis: calc(100% / 7)">
        <div v-show="date.isBetween" :class="variantCls.dateRangeBetween" style="top: 0.25rem">
          <div v-show="date.isBetweenFirst" :class="variantCls.dateRangeStart" style="right: 100%" />
          <div v-show="date.isBetweenLast" :class="variantCls.dateRangeEnd" style="left: 100%" />
        </div>
        <button class="relative z-10" :class="date.class" :disabled="date.disabled || disabled" @click="selectDate(date)">
          {{ date.date }}
        </button>
      </div>
    </section>
  </section>
</template>

<script>
import dayjs from "dayjs"
const yyyymmddRegex = /([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/
const isDateString = (currentValue) => typeof currentValue === "string" && yyyymmddRegex.test(currentValue)
const isDate = (currentValue) => currentValue instanceof Date && typeof currentValue.getFullYear === "function"
function pad(n) {
  return n.toString().length === 1 ? `0${n.toString()}` : n.toString()
}
function serializeDate(val) {
  if (!isDate(val)) {
    if (isDateString(val)) return val
    return
  }
  return `${val.getFullYear()}-${pad(val.getMonth() + 1)}-${pad(val.getDate())}`
}
export default {
  name: "EcCalendar",
  emits: ["update:modelValue"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    modelValue: {
      type: [Date, String, Array, Object],
      validator(val) {
        return (
          isDateString(val) ||
          isDate(val) ||
          (Array.isArray(val) && val.length === 0) || // Empty array
          (Array.isArray(val) && (val.every(isDateString) || val.every(isDate)))
        )
      },
      default() {
        return new Date()
      },
    },
    mode: {
      type: String,
      validator(value) {
        return ["single", "multiple", "range"].includes(value)
      },
      default() {
        return "single"
      },
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    minDate: {
      type: [Date, String, Array, Object],
      required: false,
    },
    maxDate: {
      type: [Date, String, Array, Object],
      required: false,
    },
    years: {
      type: Array,
      required: false,
      default: () => [...Array(130)].map((_, i) => new Date().getFullYear() - i),
    },
  },
  data() {
    return {
      viewingMonth: {
        year: new Date().getFullYear(),
        month: new Date().getMonth(),
      },
      selectedDates: [],
      locale: "en",
    }
  },
  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcCalendar",
          variant: this.variant,
        })?.el ?? {}
      )
    },
    serializedValue() {
      return (Array.isArray(this.modelValue) ? this.modelValue : [this.modelValue]).map(serializeDate)
    },
    valueType() {
      return this.mode !== "single" ? "array" : isDate(this.modelValue) ? "date" : "string"
    },
    months() {
      return [...Array(12)]
        .map((_, i) => Intl.DateTimeFormat(this.locale, { month: "long" }).format(new Date(new Date().getFullYear(), i)))
        .map((label, value) => ({ label, value }))
    },
    weekdays() {
      const offset = new Date().getDay()
      return [...Array(7)].map((_, i) =>
        Intl.DateTimeFormat(this.locale, { weekday: "short" }).format(
          new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - offset + i)
        )
      )
    },
    monthView() {
      const offset = new Date(this.viewingMonth.year, this.viewingMonth.month, 1).getDay() - 1
      return [...Array(42)]
        .map((_, i) => new Date(this.viewingMonth.year, this.viewingMonth.month, i - offset))
        .map((date) => {
          const d = date.getDate()
          const m = date.getMonth()
          const y = date.getFullYear()
          function pad(n) {
            return n.toString().length === 1 ? `0${n.toString()}` : n.toString()
          }
          const serialized = `${y}-${pad(m + 1)}-${pad(d)}`
          return {
            date: d,
            month: m,
            year: y,
            serialized: serializeDate(date),
            disabled: new Date(y, m, d) < this.minDate || new Date(y, m, d) > this.maxDate,
            class: this.serializedValue.includes(serialized)
              ? this.variantCls?.selectedDate
              : new Date(y, m, d) < this.minDate || new Date(y, m, d) > this.maxDate
              ? this.variantCls?.disabledDate
              : m === this.viewingMonth.month
              ? this.variantCls?.dateOfActiveMonth
              : this.variantCls?.date,
            current: Boolean(date.getMonth() === this.viewingMonth.month),
            ariaLabel: `${Intl.DateTimeFormat(this.locale, {
              day: "numeric",
            }).format(date)}, ${Intl.DateTimeFormat(this.locale, {
              year: "numeric",
              weekday: "long",
              month: "long",
            }).format(date)}`,
            isBetween: this.isBetween(date),
            isBetweenFirst: this.isBetweenFirst(date),
            isBetweenLast: this.isBetweenLast(date),
          }
          // aria-label="12, Wednesday December 2018, Dee Dee's birthday"
        })
    },
  },
  watch: {
    modelValue: {
      immediate: true,
      handler(val) {
        if (val && val instanceof Date && typeof val.getFullYear === "function") {
          this.viewingMonth = {
            year: val.getFullYear(),
            month: val.getMonth(),
          }
        } else if (val && typeof val === "string" && yyyymmddRegex.test(val)) {
          this.viewingMonth = {
            year: parseInt(val.substr(0, 4)),
            month: parseInt(val.substr(5, 2)) - 1,
          }
        }
      },
    },
  },
  methods: {
    decrementMonth(viewingMonth) {
      if (this.disabled) return
      if (viewingMonth.month > 0) {
        viewingMonth.month -= 1
      } else if (this.years.includes(viewingMonth.year - 1)) {
        viewingMonth.month = 11
        viewingMonth.year -= 1
      }
    },
    incrementMonth(viewingMonth) {
      if (this.disabled) return
      if (viewingMonth.month < 11) {
        viewingMonth.month += 1
      } else if (this.years.includes(viewingMonth.year + 1)) {
        viewingMonth.month = 0
        viewingMonth.year += 1
      }
    },
    selectDate({ date, month, year, serialized }) {
      if (this.disabled) return
      switch (this.mode) {
        case "single":
          this.$emit("update:modelValue", this.valueType === "string" ? serialized : dayjs(new Date(year, month, date)))
          break
        case "multiple":
          this.multipleSelect({ date, month, year, serialized })
          break
        case "range":
          this.rangeSelect({ date, month, year, serialized })
          break
      }
    },
    rangeSelect({ date, month, year, serialized }) {
      if (this.disabled) return
      const typedInputValue = this.modelValue[0] && isDate(this.modelValue[0]) ? new Date(year, month, date) : serialized
      const newVal = this.modelValue.length === 1 ? [...this.modelValue, typedInputValue].sort() : [typedInputValue]
      this.$emit("update:modelValue", newVal)
    },
    multipleSelect({ date, month, year, serialized }) {
      if (this.disabled) return
      const typedInputValue =
        this.modelValue && this.modelValue[0] && isDate(this.modelValue[0]) ? new Date(year, month, date) : serialized
      const newVal = this.modelValue.includes(typedInputValue)
        ? this.modelValue.filter((x) => x !== typedInputValue)
        : [...this.modelValue, typedInputValue].sort()
      this.$emit("update:modelValue", newVal)
    },
    isBetween(date) {
      const currentDate = dayjs(date)
      if (this.mode === "range" && Array.isArray(this.modelValue) && this.modelValue.length === 2) {
        const beforeDate = dayjs(this.modelValue[0])
        const afterDate = dayjs(this.modelValue[1])
        return currentDate.isAfter(beforeDate) && currentDate.isBefore(afterDate)
      } else {
        return false
      }
    },
    isBetweenFirst(date) {
      if (this.isBetween(date)) {
        const currentDate = dayjs(date)
        const beforeDate = dayjs(this.modelValue[0])
        return currentDate.isSame(beforeDate.add(1, "day"))
      }
      return false
    },
    isBetweenLast(date) {
      if (this.isBetween(date)) {
        const currentDate = dayjs(date)
        const afterDate = dayjs(this.modelValue[1])
        return currentDate.isSame(afterDate.subtract(1, "day"))
      }
      return false
    },
  },
}
</script>
