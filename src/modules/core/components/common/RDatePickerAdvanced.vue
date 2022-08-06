<template>
  <EcBox>
    <EcFlex variant="basic">
      <EcLabel class="font-medium">{{ label }}</EcLabel>
      <EcText class="text-cError-500 cursor-pointer select-none focus:outline-none" @click="clearDate">
        {{ clearLabel }}
      </EcText>
    </EcFlex>
    <!-- Date Picker Group -->
    <EcFlex class="-mx-2 flex-wrap">
      <EcBox class="w-full sm:w-4/12 px-2">
        <RFormInput
          :modelValue="modelValue.type"
          componentName="EcSelect"
          :allowSelectNothing="false"
          :options="dateTypeOptions"
          @input="handleChangeType($event)"
        />
      </EcBox>
      <EcBox class="w-full sm:w-8/12 px-2">
        <RFormInput
          :modelValue="modelValue.value"
          componentName="EcDatePicker"
          :mode="modelValue.type === 'between' ? 'range' : 'single'"
          :placeholder="$t('transaction.selectDate')"
          iconSuffix="Calendar"
          :minDate="minDate"
          :maxDate="maxDate"
          @update:modelValue="handleInput($event)"
        />
      </EcBox>
    </EcFlex>
    <!-- Quick options -->
    <EcFlex class="flex-wrap mt-2">
      <EcBox v-for="item in quickOptions" :key="item.value" :class="getQuickOptionClass(item)" @click="quickSet(item.value)">
        <EcText>{{ item.label }}</EcText>
      </EcBox>
    </EcFlex>
  </EcBox>
</template>

<script>
import dayjs from "dayjs"

export default {
  name: "RDatePickerAdvanced",
  emits: ["update:modelValue"],
  props: {
    /**
     * Date picker value
     * - type // between - before - after
     * - value
     * - quickOptionValue
     */
    modelValue: {
      type: Object,
      default: () => {
        return {
          type: "between",
        }
      },
    },
    label: {
      type: String,
      default: "",
    },
    clearLabel: {
      type: String,
      default: "Clear",
    },
    dateTypeOptions: {
      type: Array,
      default: () => [],
    },
    quickOptions: {
      type: Array,
      default: () => [],
    },
  },
  computed: {
    minDate() {
      return dayjs().subtract(65, "year")
    },
    maxDate() {
      return dayjs()
    },
  },
  methods: {
    clearDate() {
      this.$emit("update:modelValue", {
        type: "between",
        value: [],
        quickOptionValue: "",
      })
    },
    handleChangeType(event) {
      const type = event.target.value
      this.$emit("update:modelValue", {
        type,
        value: type === "between" ? [] : null,
        quickOptionValue: this.modelValue.quickOptionValue,
      })
    },
    handleInput(value) {
      this.$emit("update:modelValue", {
        type: this.modelValue.type,
        value,
        quickOptionValue: this.modelValue.quickOptionValue,
      })
    },
    getQuickOptionClass(item) {
      return [
        "text-sm cursor-pointer px-3 py-2 rounded-full select-none",
        item.value === this.modelValue.quickOptionValue ? "bg-c1-500 text-cWhite" : "bg-cWhite text-cBlack",
      ]
    },
    quickSet(quickOptionValue) {
      const computedValue = {
        type: "between",
        value: null,
        quickOptionValue,
      }
      const today = dayjs().set("hour", 0).set("minute", 0).set("second", 0)
      if (quickOptionValue === "today") {
        computedValue.value = [today, today]
      } else if (quickOptionValue === "lastWeek") {
        computedValue.value = [dayjs().set("hour", 0).set("minute", 0).set("second", 0).add(-1, "week"), today]
      } else if (quickOptionValue === "lastMonth") {
        computedValue.value = [dayjs().set("hour", 0).set("minute", 0).set("second", 0).add(-1, "month"), today]
      } else if (quickOptionValue === "last3Months") {
        computedValue.value = [dayjs().set("hour", 0).set("minute", 0).set("second", 0).add(-3, "month"), today]
      } else if (quickOptionValue === "lastYear") {
        computedValue.value = [dayjs().set("hour", 0).set("minute", 0).set("second", 0).add(-1, "year"), today]
      }
      this.$emit("update:modelValue", computedValue)
    },
  },
}
</script>
