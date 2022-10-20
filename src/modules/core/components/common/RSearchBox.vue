<template>
  <EcBox class="relative">
    <input
      type="text"
      :value="modelValue"
      :class="[isSearched ? 'pr-20' : 'pr-12', variantCls.textBox]"
      class="bg-cWhite font-medium text-c0-500 pl-4 h-8 border border-c0-300 w-full rounded-lg focus:outline-none focus:border-c1-500"
      :placeholder="placeholder"
      @focus="handleFocus()"
      @blur="handleBlur()"
      @input="handleInput($event)"
      @keyup="handleKeyup($event)"
    />
    <EcFlex
      v-if="isSearched"
      class="absolute justify-center items-center rounded-full w-5 h-5 bg-c0-100 text-c1-500 cursor-pointer"
      :style="clearSearchStyle"
      @click="handleClearSearch()"
    >
      <EcIcon :width="variantCls.crossSize" :height="variantCls.crossSize" icon="X" />
    </EcFlex>
    <EcFlex
      :class="isFocus ? 'text-c1-500' : 'text-c1-200'"
      class="absolute right-0 inset-y-0 justify-center items-center px-3 cursor-pointer hover:text-c1-500"
      @click="$emit('search', value)"
    >
      <EcBox class="absolute left-0 h-10 bg-c0-300" style="width: 1px; background-color: #e8edf6" />
      <EcIcon :width="variantCls.searchSize" :height="variantCls.searchSize" icon="Search" />
    </EcFlex>
  </EcBox>
</template>

<script>
export default {
  name: "RSearchBox",
  emits: ["clear-search", "update:modelValue"],
  props: {
    modelValue: {
      type: String,
      default: null,
    },
    placeholder: {
      type: String,
      required: false,
      default: "",
    },
    isSearched: {
      type: Boolean,
      required: false,
      default: false,
    },
    variant: {
      type: String,
      default: "md",
      validators(value) {
        return ["md", "sm"].includes(value)
      },
    },
  },
  data() {
    return {
      isFocus: false,

      variants: {
        md: {
          textBox: "h-12",
          crossSize: "16",
          searchSize: "20",
        },

        sm: {
          textBox: "h-10",
          crossSize: "12",
          searchSize: "16",
        },
      },
    }
  },
  computed: {
    clearSearchStyle() {
      return {
        top: "50%",
        right: "3.5rem",
        transform: "translateY(-50%)",
      }
    },

    variantCls() {
      return this.variants?.[this.variant] ?? {}
    },
  },
  methods: {
    handleFocus() {
      this.isFocus = true
    },
    handleBlur() {
      this.isFocus = false
    },
    handleClearSearch() {
      this.$emit("clear-search")
    },
    handleInput(event) {
      this.$emit("update:modelValue", event.target.value)
    },
    handleKeyup(event) {
      if (event.key === "Escape") {
        this.handleClearSearch()
      }
    },
  },
}
</script>
