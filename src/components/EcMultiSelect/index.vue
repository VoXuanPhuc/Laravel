<template>
  <div
    v-click-outside="hideOptions"
    class="relative flex justify-between items-center"
    :class="variantCls.root"
    style="min-height: 2rem"
    @click="handleClickSelect($event)"
  >
    <!-- Tags -->
    <span v-if="tags.length === 0 && !showOptions" :class="variantCls.placeholder">
      {{ placeholder }}
    </span>
    <div v-else :class="isGroupOptions ? variantCls.tagRootGroupOptions : variantCls.tagRoot">
      <div v-for="(tag, idx) in tags" :key="tag[valueKey]" :class="variantCls.tag + ` ${tag?.tag_color || 'bg-c1-800'} `">
        <span> {{ isGroupOptions ? tag?.type + ": " : "" }} {{ tag[nameKey] }}</span>
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
        <div v-if="!isGroupOptions">
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

        <!-- Option Groups -->
        <div v-if="isGroupOptions">
          <div v-for="group in filteredGroupOptions" :key="group?.name">
            <!-- Group name -->
            <div :class="variantCls.groupLabel" @click="handleClickGroupName(group?.name)">
              {{ group?.name }}

              <EcIcon :icon="groupExpansion[group?.name] ? 'CaretDown' : 'CaretRight'" width="14" height="14" />
            </div>
            <!-- group items -->
            <div class="optionGroupItems" :class="groupExpansion[group?.name] ? '' : 'hidden'">
              <div v-for="option in group.data" :key="option[valueKey]" class="option" @click="onOptionClick(option)">
                <slot :name="`option-${option[valueKey]}`">
                  <p style="min-height: 2rem" :class="getOptionClass(option)">
                    {{ option[nameKey] }}
                  </p>
                </slot>
              </div>
            </div>
          </div>

          <!-- No option was found -->
          <div v-if="filteredGroupOptions.length === 0" :class="variantCls.noOption">
            {{ noDataPlaceholder }}
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import debounce from "lodash.debounce"
import { ref } from "vue"
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
      type: [Array, Object],
      default: () => [],
    },
    options: {
      type: [Array, Object],
      required: true,
    },

    /** Group option */
    isGroupOptions: {
      type: Boolean,
      default: false,
    },

    groupKey: {
      type: Boolean,
      default: false,
    },
    groupDataKey: {
      type: Boolean,
      default: false,
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
      groupExpansion: ref({}),
    }
  },
  computed: {
    /** Single filtered options */
    filteredOptions() {
      if (!this.searchTerm) {
        return this.options
      }
      const filteredOptions = this.options.filter((item) => {
        if (item && item[this.nameKey] && item[this.nameKey].toLowerCase().includes(this.searchTerm.toLowerCase())) {
          return true
        }

        return false
      })

      return filteredOptions
    },

    /** Group Options */
    filteredGroupOptions() {
      if (!this.searchTerm) {
        return this.options
      }

      const filteredGroupOptions = this.options.map((group) => {
        // Expand the group
        this.groupExpansion[group?.name] = true

        // Clone group
        const groupClone = { ...group }

        groupClone.data = groupClone?.data.filter((item) => {
          if (item && item[this.nameKey] && item[this.nameKey].toLowerCase().includes(this.searchTerm.toLowerCase())) {
            return true
          }

          return false
        })

        return groupClone
      })

      return filteredGroupOptions
    },

    /** Variant */
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
        if (this.isSingleSelect) {
          this.tags = this.modelValue[this.valueKey] ? [this.modelValue] : []
        } else {
          this.tags = this.modelValue || []
        }
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

    /**
     *
     * @param {*} event
     */
    hideOptions(event) {
      if (event) {
        event.stopPropagation()
      }
      this.toggleOptions(false)
      this.clearSearch()
    },

    /**
     *
     */
    handleClickGroupName(groupName) {
      this.groupExpansion[groupName] = !this.groupExpansion[groupName]
    },

    /**
     *
     * @param {*} option
     */
    onOptionClick(option) {
      this.addTag(option)
      this.toggleOptions(false)

      this.searchTerm = ""

      if (this.isSingleSelect) {
        this.$emit("update:modelValue", this.tags[0])
      } else {
        this.$emit("update:modelValue", this.tags)
      }
    },

    /**
     *
     * @param {*} tag
     */
    addTag(tag) {
      if (this.isSingleSelect) {
        this.tags = [tag]
        return
      }
      if (this.tags.find((t) => t[this.valueKey] === tag[this.valueKey])) return
      this.tags.push(tag)
    },

    /**
     *
     * @param {*} idx
     */
    removeTag(idx) {
      if (this.disabled) return

      this.tags.splice(idx, 1)
      this.$emit("update:modelValue", this.tags)
    },

    /**
     * Clear search
     */
    clearSearch() {
      this.searchTerm = ""
    },

    /**
     * Handle input
     */
    handleInputSearch: debounce(function () {
      this.$emit("search", this.searchTerm)
    }, 400),

    /**
     *
     * @param {*} option
     */
    getOptionClass(option) {
      return this.tags.includes(option) ? this.variantCls?.selectedOption : this.variantCls?.option
    },
  },
}
</script>
