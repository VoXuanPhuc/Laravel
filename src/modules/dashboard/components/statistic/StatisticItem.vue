<template>
  <EcBox variant="card-2" class="mb-4 mr-3 w-full md:w-4/12 lg:inline-flex lg:flex-grow lg:w-auto" style="min-width: 12rem">
    <!-- Icon -->
    <EcFlex class="relative justify-center items-center p-4 rounded-full w-16 h-16 overflow-hidden">
      <EcBox class="absolute top-0 left-0 w-full h-full" :class="`${info.backgroundColor}`" />
      <EcIcon width="32" height="32" class="relative" :class="`${info.iconColor}`" :icon="info.icon" />
    </EcFlex>

    <!-- Info -->
    <EcBox class="mt-4 lg:mt-0 lg:ml-6">
      <EcText class="font-medium text-cBlack text-base">
        {{ info.label }}
      </EcText>
      <EcFlex class="items-center mt-2">
        <EcText v-if="!isLoading.status" class="font-medium text-5xl text-c0-500">
          {{ info.count }}
        </EcText>
        <EcSpinner v-else />
        <EcBox v-if="info.navigation" class="ml-2">
          <EcButton variant="transparent-rounded" v-tooltip="info.btnTooltip" @click="handleClickAdd">
            <EcIcon icon="PlusCircle" width="20" height="20" />
          </EcButton>
        </EcBox>
      </EcFlex>
    </EcBox>
  </EcBox>
</template>

<script>
import EcSpinner from "@/components/EcSpinner/index.vue"
export default {
  name: "StatisticItem",
  inject: ["isLoading"],
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
  components: { EcSpinner },
}
</script>
