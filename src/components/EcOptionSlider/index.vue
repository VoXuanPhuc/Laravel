<template>
  <div :class="variantCls.root">
    <div :class="variantCls.selectedLabel">
      {{ options[valueIdx]?.name }}
    </div>
    <input
      type="range"
      class="slider"
      :class="variantCls.slider"
      list="options"
      :disabled="disabled"
      :value="valueIdx"
      :min="0"
      :max="options.length - 1"
      step="1"
      ref="slider"
      @input="handleRangeChange($event.target.value)"
    />

    <datalist class="optionList" :class="variantCls.label" id="options">
      <option v-for="(opt, idx) in options" :key="idx" :value="idx" :label="opt.name"></option>
    </datalist>
  </div>
</template>

<style>
.optionList {
  justify-content: space-between;
}

/* The slider itself */
.slider {
  -webkit-transition: 0.2s; /* 0.2 seconds transition on hover */
  transition: opacity 0.2s;
}

/* The slider handle (use -webkit- (Chrome, Opera, Safari, Edge) and -moz- (Firefox) to override default look) */
.slider::-webkit-slider-thumb {
  -webkit-appearance: none; /* Override default look */
  appearance: none;
  width: 20px; /* Set a specific slider handle width */
  height: 20px; /* Slider handle height */
  background: #3860e2; /* Green background */
  border-radius: 100%;
  cursor: pointer; /* Cursor on hover */
}

.slider::-moz-range-thumb {
  width: 20px; /* Set a specific slider handle width */
  height: 20px; /* Slider handle height */
  background: #3860e2; /* Green background */
  border-radius: 100%;
  cursor: pointer; /* Cursor on hover */
}
</style>
<script>
// import { watchEffect } from "vue"

export default {
  name: "EcOptionSlider",
  emits: ["update:modelValue", "blur", "focus"],

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

  setup(props) {
    const valueIdx = 0

    // watchEffect(() => {
    //   const foundIndex = props.options?.findIndex((item) => {
    //     return item[props.valueKey] === props.modelValue[props.valueKey]
    //   })

    //   if (foundIndex) {
    //     valueIdx = foundIndex
    //   }
    // })

    return {
      valueIdx,
    }
  },
  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcOptionSlider",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
  methods: {
    /**
     *
     * @param {*} value
     */
    handleRangeChange(value) {
      this.valueIdx = value
      this.$emit("update:modelValue", this.options[this.valueIdx])
    },
  },

  watch: {
    modelValue(data) {
      const foundIndex = this.options?.findIndex((item) => {
        return item[this.valueKey] === data[this.valueKey]
      })

      if (foundIndex) {
        this.valueIdx = foundIndex
      }
    },
  },
}
</script>
