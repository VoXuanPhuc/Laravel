<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("activity.activities") }}
        </EcHeadline>
        <EcButton variant="primary-sm" class="mb-3 lg:mb-0" iconPrefix="PlusCircle" @click="handleClickAddActivity">
          {{ $t("activity.buttons.add") }}
        </EcButton>
      </EcFlex>
      <!-- Search box -->
      <EcFlex class="flex-grow justify-end items-center w-full md:w-auto">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('activity.search')"
          class="w-full md:max-w-xs"
          @update:modelValue="onFilter"
          @clear-search="handleClearSearch"
        />
      </EcFlex>
    </EcFlex>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"

export default {
  name: "ViewActivityList",

  data() {
    return {
      searchQuery: "",
      onFilter: "",
    }
  },
  methods: {
    handleClearSearch() {},
    handleClickAddActivity() {
      goto("ViewActivityNew")
    },
  },
}
</script>
