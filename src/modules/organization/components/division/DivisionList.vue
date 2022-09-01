<template>
  <EcBox class="overflow-x-auto lg:overflow-visible mt-8 lg:mt-8">
    <!-- Search box -->
    <EcFlex class="flex-grow justify-end items-center w-full md:w-auto mb-4 mr-4">
      <RSearchBox
        v-model="searchQuery"
        :isSearched="searchQuery !== ''"
        :placeholder="$t('organization.search')"
        class="w-full md:max-w-xs"
        @update:modelValue="onFilter"
        @clear-search="handleClearSearch"
      />
    </EcFlex>
    <!-- Divison list -->

    <EcFlex class="lg:flex-wrap grid grid-cols-1 md:grid-cols-2 gap-2">
      <DivisionListCardItem
        v-for="item in listData"
        :division="item"
        :key="item.uid"
        :isActive="selectedCardId === item.uid"
        @handleCardChange="handleCardChange"
      />
    </EcFlex>
  </EcBox>

  <!-- No data found -->
  <EcBox v-show="listData.length === 0">
    <EcText>{{ $t("core.noDataHere") }}</EcText>
  </EcBox>
</template>

<script>
import DivisionListCardItem from "./DivisionListCardItem"

export default {
  name: "DivisionList",
  components: {
    DivisionListCardItem,
  },
  data() {
    return {
      searchQuery: "",
      onFilter: "",
      selectedCardId: "",
    }
  },
  props: {
    listData: {
      type: Array,
      default: () => [],
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },

  computed: {},

  methods: {
    handleCardChange(cardId) {
      this.selectedCardId = cardId
    },

    handleClearSearch() {},
  },
}
</script>
