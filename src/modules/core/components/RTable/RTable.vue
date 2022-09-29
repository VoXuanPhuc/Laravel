<template>
  <EcBox class="relative">
    <EcFlex v-if="isLoading" class="justify-center">
      <EcSpinner variant="secondary-lg" type="dots" />
    </EcFlex>
    <EcBox v-else>
      <!-- Table -->
      <EcBox v-show="list.length > 0" ref="tableWrapper" class="h-screen overflow-x-auto">
        <table ref="table" class="w-full border-collapse border-none">
          <thead>
            <slot name="header">
              <tr>
                <th
                  v-for="(item, i) in header"
                  :key="i"
                  class="px-6 py-4 text-left whitespace-no-wrap text-c1-800 md:font-medium"
                >
                  {{ item.label }}
                </th>
              </tr>
            </slot>
          </thead>
          <tbody class="relative overflow-hidden rounded-lg shadow bg-cWhite">
            <template v-for="(item, index) in list" :key="item?.id ?? index">
              <slot :item="item" :last="index === list.length - 1" :first="index === 0" :even="index % 2 === 0" />
            </template>
          </tbody>
        </table>
      </EcBox>
      <!-- Scrollable Arrow -->
      <EcFlex v-if="isScrollable" class="absolute top-0 right-0 items-center h-16 p-4 pr-6 text-c1-200">
        <EcIcon icon="ChevronDoubleRight" />
      </EcFlex>
      <!-- Loading & Empty Table -->
      <EcBox v-show="list.length === 0">
        <EcText>{{ $t("core.noDataHere") }}</EcText>
      </EcBox>
    </EcBox>
  </EcBox>
</template>

<script>
import { ref, watch, nextTick } from "vue"

export default {
  name: "RTable",
  props: {
    header: {
      type: Array,
      default: () => [],
    },
    list: {
      type: Array,
      default: () => [],
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },

  setup(props) {
    const tableWrapper = ref(null)
    const table = ref(null)

    const isScrollable = ref(false)

    function checkScrollable() {
      nextTick(() => {
        const tableWrapperWidth = tableWrapper.value?.$el?.clientWidth
        const tableWidth = table.value?.clientWidth
        // Check if table has horizontal scroll
        isScrollable.value = tableWidth > tableWrapperWidth
      })
    }

    watch(
      () => props.list,
      (value) => {
        if (value.length > 0) checkScrollable()
      }
    )

    watch(
      () => props.isLoading,
      (newVal, oldVal) => {
        if (oldVal === true && newVal === false) checkScrollable()
      }
    )

    return { tableWrapper, table, isScrollable, checkScrollable }
  },
}
</script>
