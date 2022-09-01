<template>
  <EcBox class="overflow-x-auto lg:overflow-visible mt-8 lg:mt-24">
    <EcFlex class="lg:flex-wrap grid sm:grid-cols-2 md:grid-cols-3 gap-2">
      <OrganizationListCardItem v-for="item in listData" :organization="item" :key="item.name" />
    </EcFlex>
  </EcBox>

  <!-- No data found -->
  <EcBox v-show="listData.length === 0">
    <EcText>{{ $t("core.noDataHere") }}</EcText>
  </EcBox>
</template>

<script>
import OrganizationListCardItem from "./OrganizationListCardItem"

export default {
  name: "OrganizationList",
  components: {
    OrganizationListCardItem,
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
}
</script>
