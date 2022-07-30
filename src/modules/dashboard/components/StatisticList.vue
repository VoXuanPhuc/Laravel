<template>
  <EcBox class="overflow-x-auto lg:overflow-visible mt-8">
    <EcFlex class="lg:flex-wrap">
      <StatisticItem v-for="item in listData" :info="item" :key="item.name" />
    </EcFlex>
  </EcBox>
</template>

<script>
import StatisticItem from "./StatisticItem"

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
