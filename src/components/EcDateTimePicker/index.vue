<template>
  <Datepicker
    :class="variantCls.root"
    :disabled="disabled"
    v-model="date"
    :format="format"
    @update:modelValue="handleChangeDate"
    utc
  />
</template>

<style>
.dp__select {
  color: #3860e2;
}
</style>
<script>
import Datepicker from "@vuepic/vue-datepicker"
import "@vuepic/vue-datepicker/dist/main.css"
import { ref } from "vue"

export default {
  name: "EcDateTimePicker",
  components: { Datepicker },
  props: {
    variant: {
      type: String,
      default: "default",
    },
    modelValue: {
      required: true,
      default: "",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    format: {
      type: String,
      default: "dd/MM/yyyy HH:mm",
    },
  },

  computed: {
    computedListeners() {
      return this.disabled ? {} : this.$attrs
    },
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcInputTime",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },

  setup() {
    const date = ref()

    return {
      date,
    }
  },

  methods: {
    handleChangeDate(modelData) {
      this.$emit("update:modelValue", modelData.toString())
    },
  },
}
</script>
