<template>
  <EcBox class="overflow-x-auto lg:overflow-visible mt-8 lg:mt-24">
    <EcFlex class="lg:flex-wrap grid sm:grid-cols-2 md:grid-cols-3 gap-2">
      <IndustryListCartItem v-for="item in listData" :key="item.uid" :industry="item" v-bind="$attrs" />
    </EcFlex>
  </EcBox>
  <!-- No data found -->
  <EcBox v-show="listData.length === 0">
    <EcText>{{ $t("core.noDataHere") }}</EcText>
  </EcBox>
</template>

<script>
import IndustryListCartItem from "./IndustryListCardItem"

export default {
  name: "IndustryList",
  components: {
    IndustryListCartItem,
  },
  props: {
    listData: {
      type: Array,
      default: () => [],
    },
  },
}
</script>
