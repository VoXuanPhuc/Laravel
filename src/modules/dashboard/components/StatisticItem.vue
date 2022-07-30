<template>
  <EcBox variant="card-2" class="mb-4 mr-3 w-4/12 lg:inline-flex lg:flex-grow lg:w-auto" style="min-width: 12rem">
    <EcFlex class="relative justify-center items-center p-4 rounded-full w-16 h-16 overflow-hidden">
      <EcBox class="absolute top-0 left-0 w-full h-full" :class="`${info.backgroundColor}`" />
      <EcIcon width="32" height="32" class="relative" :class="`${info.iconColor}`" :icon="info.icon" />
    </EcFlex>
    <EcBox class="mt-4 lg:mt-0 lg:ml-6">
      <EcText class="font-medium text-c0-300 text-xs">
        {{ info.label }}
      </EcText>
      <EcFlex class="items-center mt-2">
        <EcText class="font-medium text-5xl text-c0-500">
          {{ info.count }}
        </EcText>
        <EcBox v-if="info.navigation" class="ml-2">
          <JButton variant="transparent-rounded" @click="handleClickAdd">
            <EcIcon icon="PlusCircle" width="20" height="20" />
          </JButton>
        </EcBox>
      </EcFlex>
    </EcBox>
  </EcBox>
</template>

<script>
export default {
  name: "StatisticItem",
  props: {
    info: {
      type: Object,
      default: () => {},
    },
  },
  methods: {
    handleClickAdd() {
      // Go to add new page
      this.$router.push({
        name: this.info?.navigation?.name,
      })
    },
  },
}
</script>
