<template>
  <div
    v-click-outside="hideOptions"
    class="relative flex justify-between items-center"
    :class="variantCls.root"
    style="min-height: 2.5rem"
    @click="handleClickSelect($event)"
  >
    <!-- Tags -->
    <span v-if="tags.length === 0 && !showOptions" :class="variantCls.placeholder">
      {{ placeholder }}
    </span>
    <div v-else :class="variantCls.tagRoot">
      <div v-for="(tag, idx) in tags" :key="tag[valueKey]" :class="variantCls.tag">
        <span>{{ tag[nameKey] }}</span>
        <span class="cursor-pointer" :class="variantCls.tagRemove" @click.stop="removeTag(idx)">&times;</span>
      </div>
    </div>
    <!-- Options -->
    <transition
      :enterClass="variantCls.transition.enter"
      :enterActiveClass="variantCls.transition.enterActive"
      :leaveActiveClass="variantCls.transition.leaveActive"
      :leaveToClass="variantCls.transition.leave"
    >
      <div
        v-if="showOptions"
        :style="variantCls.optionsStyles"
        class="absolute overflow-y-auto z-10 scrolling-touch mb-4"
        :class="variantCls.options"
      >
        <!-- Input -->
        <input
          ref="searchInput"
          class="sticky top-0"
          v-model="searchTerm"
          :class="variantCls.input"
          :placeholder="searchPlaceholder"
          :disabled="disabled"
          @input="handleInputSearch"
          @focus="handleFocus"
          @blur="handleBlur"
        />
        <!-- Options -->
        <div v-for="option in filteredOptions" :key="option[valueKey]" class="option" @click="onOptionClick(option)">
          <slot :name="`option-${option[valueKey]}`">
            <p style="min-height: 2rem" :class="getOptionClass(option)">
              {{ option[nameKey] }}
            </p>
          </slot>
        </div>
        <!-- No option was found -->
        <div v-if="filteredOptions.length === 0" :class="variantCls.noOption">
          {{ noDataPlaceholder }}
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import debounce from "lodash/debounce"
export default {
  name: "EcMultiSelect",
  emits: ["update:modelValue", "search", "focus", "blur"],
  props: {
    variant: {
      type: String,
      default: "default",
    },
    nameKey: {
      type: String,
      default: "name",
    },
    valueKey: {
      type: String,
      default: "value",
    },
    modelValue: {
      type: Array,
      default: () => [],
    },
    options: {
      type: Array,
      required: true,
      default: () => [],
    },
    placeholder: {
      type: String,
      default: "Please select",
    },
    searchPlaceholder: {
      type: String,
      default: "Type to search",
    },
    noDataPlaceholder: {
      type: String,
      default: () => "No data found",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    isSingleSelect: {
      type: Boolean,
      default: false,
    },
    iconCollapsed: {
      type: String,
    },
    iconExpanded: {
      type: String,
    },
  },
  data() {
    return {
      tags: [],
      searchTerm: "",
      showOptions: false,
    }
  },
  computed: {
    filteredOptions() {
      if (!this.searchTerm) return this.options
      const filteredOptions = this.options.filter((item) => {
        if (item && item[this.nameKey] && item[this.nameKey].toLowerCase().includes(this.searchTerm.toLowerCase())) {
          return true
        }
      })
      return filteredOptions
    },
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcMultiSelect",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
  watch: {
    modelValue: {
      handler() {
        this.tags = this.modelValue || []
      },
      immediate: true,
      deep: true,
    },
  },
  methods: {
    handleClickSelect(event) {
      event.stopPropagation()
      if (!this.disabled) {
        this.toggleOptions(true)
        // Focus on search input after render it
        this.$nextTick(
          function () {
            this.$refs.searchInput.focus()
          }.bind(this)
        )
      }
    },
    handleFocus() {
      if (!this.disabled) {
        this.$emit("focus")
        this.toggleOptions(true)
      }
    },
    handleBlur() {
      this.$emit("blur")
    },
    emit(...args) {
      if (!this.disabled) {
        this.$emit(...args)
      }
    },
    toggleOptions(value) {
      if (value === undefined) this.showOptions = !this.showOptions
      this.showOptions = value
    },
    hideOptions(event) {
      if (event) {
        event.stopPropagation()
      }
      this.toggleOptions(false)
      this.clearSearch()
    },
    onOptionClick(option) {
      this.addTag(option)
      this.toggleOptions(false)
      this.searchTerm = ""
      this.$emit("update:modelValue", this.tags)
    },
    addTag(tag) {
      if (this.isSingleSelect) {
        this.tags = [tag]
        return
      }
      if (this.tags.find((t) => t[this.valueKey] === tag[this.valueKey])) return
      this.tags.push(tag)
    },
    removeTag(idx) {
      if (this.disabled) return
      this.tags.splice(idx, 1)
      this.$emit("update:modelValue", this.tags)
    },
    clearSearch() {
      this.searchTerm = ""
    },
    handleInputSearch: debounce(function () {
      this.$emit("search", this.searchTerm)
    }, 400),
    getOptionClass(option) {
      return this.tags.includes(option) ? this.variantCls?.selectedOption : this.variantCls?.option
    },
  },
}
</script>
