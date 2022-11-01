<template>
  <!-- BIA  -->
  <EcBox class="w-full md:w-8/12 mt-8">
    <SummaryRowSkeleton :rows="2" :loading="isLoading">
      <EcFlex class="items-center mb-2" v-for="(bia, idx) in bias" :key="bia.uid">
        <!-- BIA Row -->
        <EcInputText
          v-model="bias[idx].name"
          variant="primary-dashboard"
          componentName="EcInputText"
          field="form.name"
          class="w-8/12 text-sm"
          v-tooltip="{ text: 'Enter to save' }"
        />
        <EcButton
          variant="wrapper"
          :class="[getStatus(bia)?.tag_color, getStatus(bia)?.tag_text_color || 'text-cWhite']"
          class="ml-3 w-4/12 text-sm rounded-lg px-5 py-3 text-cBlack justify-center border-0"
        >
          {{ getStatus(bia)?.name }}
        </EcButton>
      </EcFlex>
    </SummaryRowSkeleton>
  </EcBox>
</template>

<script>
import { useTopBIA } from "../../use/useTopBIA"
import { useBIAStatusEnum } from "@/modules/assessment/use/useBIAStatusEnum"
import SummaryRowSkeleton from "./SummaryRowSkeleton.vue"

export default {
  name: "DashboardSummaryBIA",

  data() {
    return {
      isLoading: false,
      bias: [],
    }
  },
  setup() {
    const { getTopBIAs } = useTopBIA()
    const { statuses } = useBIAStatusEnum()

    return { getTopBIAs, statuses }
  },
  mounted() {
    this.fetchTopBIAs()
  },
  methods: {
    /** Fetch top 2 BIA */
    async fetchTopBIAs() {
      this.isLoading = true

      const data = await this.getTopBIAs()
      if (data) {
        this.bias = data
        this.isLoading = false
      }
    },

    /**
     *
     * @param {*} bia
     */
    getStatus(bia) {
      return (
        this.statuses.find((item) => {
          return item.value === bia?.status
        }) || { name: "Unknown" }
      )
    },
  },
  components: { SummaryRowSkeleton },
}
</script>
