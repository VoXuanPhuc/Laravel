<template>
  <div :class="variantCls.root">
    <EcSelect
      :variant="componentInstanceVariants.daySelect"
      :class="variantCls.day"
      :value="computedDay"
      :options="dayOptions"
      :placeholder="dayPlaceholder"
      :allowSelectNothing="true"
      :disabled="disabled"
      @input=";(day = $event), onInput()"
      @focus="emit('focus')"
      @blur="emit('blur')"
    />
    <EcSelect
      :variant="componentInstanceVariants.monthSelect"
      :class="variantCls.month"
      :value="computedMonth"
      :options="monthOptions"
      :placeholder="monthPlaceholder"
      :allowSelectNothing="true"
      :disabled="disabled"
      @input=";(month = $event), onInput()"
      @focus="emit('focus')"
      @blur="emit('blur')"
    />
    <EcSelect
      :variant="componentInstanceVariants.yearSelect"
      :class="variantCls.year"
      :value="computedYear"
      :options="yearOptions"
      :placeholder="yearPlaceholder"
      :allowSelectNothing="true"
      :disabled="disabled"
      @input=";(year = $event), onInput()"
      @focus="emit('focus')"
      @blur="emit('blur')"
    />
  </div>
</template>

<script>
import dayjs from "dayjs"
import EcSelect from "../EcSelect/index.vue"

export default {
  components: { EcSelect },

  props: {
    variant: {
      type: String,
      default: "default",
    },
    subVariants: {
      type: Object,
      required: false,
      default: () => ({
        daySelect: "default",
        monthSelect: "default",
        yearSelect: "default",
      }),
    },
    value: {
      validator(value) {
        return value === null || typeof value === "string" || dayjs(value).isValid()
      },
      required: true,
      default: null,
    },
    dayPlaceholder: {
      type: String,
      default: "Day",
      required: false,
    },
    monthPlaceholder: {
      type: String,
      default: "Month",
      required: false,
    },
    yearPlaceholder: {
      type: String,
      default: "Year",
      required: false,
    },
    daysNames: {
      type: Array,
      required: false,
      default: () => [
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "10",
        "11",
        "12",
        "13",
        "14",
        "15",
        "16",
        "17",
        "18",
        "19",
        "20",
        "21",
        "22",
        "23",
        "24",
        "25",
        "26",
        "27",
        "28",
        "29",
        "30",
        "31",
      ],
    },
    monthsNames: {
      type: Array,
      required: false,
      default: () => [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
    },
    maxAge: {
      type: Number,
      required: false,
      default: 150,
    },
    minAge: {
      type: Number,
      required: false,
      default: 0,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      day: this.getDayFromValue(),
      month: this.getMonthFromValue(),
      year: this.getYearFromValue(),
      dayOptions: this.getDaysOptions(),
      monthOptions: this.getMonthsOptions(),
      yearOptions: this.getYearsOptions(),
    }
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcInputDateOfBirth",
          variant: this.variant,
        })?.el ?? {}
      )
    },
    componentInstanceVariants() {
      return (
        this.getComponentVariants({
          componentName: "EcInputDateOfBirth",
          variant: this.variant,
        })?.componentInstanceVariants ?? {}
      )
    },

    computedDay() {
      return this.getDayFromValue()
    },
    computedMonth() {
      return this.getMonthFromValue()
    },
    computedYear() {
      return this.getYearFromValue()
    },
    ddPlaceholder() {
      return this.dayPlaceholder ? this.dayPlaceholder : this.$t("date.day")
    },
    mmPlaceholder() {
      return this.monthPlaceholder ? this.monthPlaceholder : this.$t("date.month")
    },
    yyyyPlaceholder() {
      return this.yearPlaceholder ? this.yearPlaceholder : this.$t("date.year")
    },
  },

  methods: {
    emit(...args) {
      if (!this.disabled) {
        this.$emit(...args)
      }
    },

    getDayFromValue() {
      if (dayjs(this.value).isValid() === false) return this.day
      return dayjs(this.value).format("DD")
    },

    getMonthFromValue() {
      if (dayjs(this.value).isValid() === false) return this.month
      return dayjs(this.value).format("MM")
    },

    getYearFromValue() {
      if (dayjs(this.value).isValid() === false) return this.year
      return dayjs(this.value).format("YYYY")
    },

    getYearsOptions() {
      const output = []
      let from = parseInt(
        dayjs()
          .subtract(this.maxAge + 1, "year")
          .format("YYYY")
      )
      const to = this.minAge ? parseInt(dayjs().subtract(this.minAge, "year").format("YYYY")) : parseInt(dayjs().format("YYYY"))

      while (from <= to) {
        output.push({
          name: `${from}`,
          value: from.toString().length === 1 ? `0${from}` : `${from}`,
        })
        from++
      }

      return output
    },

    getMonthsOptions() {
      return this.monthsNames.map((item, index) => {
        return {
          name: new Date(2000, index, 1).toLocaleDateString(this.$store.state.locale, { month: "long" }) || item,
          value: index + 1 < 10 ? `0${index + 1}` : `${index + 1}`,
        }
      })
    },

    getDaysOptions() {
      return this.daysNames.map((item, index) => {
        return {
          name: item,
          value: index + 1 < 10 ? `0${index + 1}` : `${index + 1}`,
        }
      })
    },

    onInput() {
      if (this.disabled) return
      let date = `${this.year}-${this.month}-${this.day}`
      date = dayjs(date).format("YYYY-MM-DD")

      // emit input only if date is valid date, otherwise return value
      if (dayjs(date).isValid() && this.day && this.month && this.year) this.$emit("input", date)
      else this.$emit("input", null)
    },
  },
}
</script>
