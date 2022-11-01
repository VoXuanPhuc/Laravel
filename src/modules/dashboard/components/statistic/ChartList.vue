<template>
  <EcBox class="overflow-x-auto lg:overflow-visible mt-8">
    <EcFlex class="grid grid-cols-1 md:grid-cols-3">
      <ChartItem v-for="item in listData" :chartInfo="item" :key="item.name" />
    </EcFlex>
  </EcBox>
</template>

<script>
import ChartItem from "@/modules/dashboard/components/statistic/ChartItem.vue"

export default {
  name: "ChartList",
  components: {
    ChartItem,
  },
  props: {
    listData: {
      type: Array,
      default: () => [],
    },
  },
}
</script>
