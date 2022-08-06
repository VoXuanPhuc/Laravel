<template>
  <EcBox class="relative">
    <EcBox class="absolute left-0 w-full bg-c1-500" style="bottom: 6px; height: 2px" />
    <EcBox class="overflow-y-auto pb-2">
      <ul class="flex text-lg font-medium">
        <li
          v-for="(option, idx) in options"
          :key="idx"
          class="cursor-pointer select-none px-8 py-2 text-center relative flex-shrink-0 mr-6"
          style="min-width: 5rem"
          @click="onChange(option)"
        >
          <EcText class="leading-tight" :class="option.name === activeItem ? 'text-c1-800' : 'text-c0-300'">
            {{ option.label }}
          </EcText>
          <EcBox
            class="absolute h-1 left-0 right-0 rounded"
            style="bottom: -2.5px"
            :class="option.name === activeItem ? 'bg-c1-800' : 'bg-cTransparent'"
          />
        </li>
      </ul>
    </EcBox>
  </EcBox>
</template>

<script>
export default {
  name: "RTabs",
  emits: ["change"],
  props: {
    options: {
      type: Array,
      default: () => [],
      required: false,
    },
    activeTab: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      activeItem: this.activeTab,
    }
  },
  methods: {
    onChange(selectedTab) {
      this.activeItem = selectedTab.name
      this.$emit("change", selectedTab)
    },
  },
}
</script>
