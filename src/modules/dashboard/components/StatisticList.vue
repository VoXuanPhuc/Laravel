<template>
  <EcBox class="overflow-x-auto lg:overflow-visible mt-8">
    <EcFlex class="grid grid-cols-1 md:grid-cols-4">
      <StatisticItem v-for="item in listData" :info="item" :key="item.name" />
    </EcFlex>
  </EcBox>
</template>

<script>
import StatisticItem from "@/modules/dashboard/components/StatisticItem.vue"

export default {
  name: "StatisticList",
  components: {
    StatisticItem,
  },
  props: {
    listData: {
      type: Array,
      default: () => [],
    },
  },
}
</script>
