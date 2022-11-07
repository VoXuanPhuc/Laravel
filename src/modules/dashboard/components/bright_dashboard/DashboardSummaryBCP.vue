<template>
  <!-- BCP  -->
  <EcBox class="w-full md:w-8/12 mt-8">
    <EcLabel class="font-semibold mt-6 mb-2">Business Continuity Plan (BCP)</EcLabel>

    <SummaryRowSkeleton :loading="isLoading" :rows="2" class="mt-2">
      <EcBox class="relative">
        <EcFlex v-for="(bcp, idx) in bcps" :key="bcp.uid" class="items-center mb-2">
          <!-- BCP Row -->
          <EcInputText
            v-model="bcps[idx].name"
            variant="primary-dashboard"
            componentName="EcInputText"
            field="form.name"
            class="w-8/12 text-base hover:cursor-auto"
            :readonly="true"
          />
          <EcButton
            variant="wrapper"
            class="ml-3 text-base w-4/12 rounded-lg px-5 py-3 justify-center"
            :class="[getStatus(bcp)?.tag_color, getStatus(bcp)?.tag_text_color || 'text-cWhite']"
            @click="handleShowContextMenu(idx, bcp)"
          >
            {{ getStatus(bcp)?.name }}
          </EcButton>
        </EcFlex>
        <!--Context menu -->
        <BCPContextMenu :statuses="statuses" ref="contextMenu"></BCPContextMenu>
      </EcBox>
    </SummaryRowSkeleton>
  </EcBox>
</template>

<script>
import { useBCPStatusEnum } from "@/modules/bcp/use/useBCPStatusEnum"
import { useTopBCP } from "../../use/useTopBCP"
import BCPContextMenu from "./BCPContextMenu.vue"
import SummaryRowSkeleton from "./SummaryRowSkeleton.vue"

export default {
  name: "DashboardSummaryBCP",

  data() {
    return {
      isLoading: false,
      bcps: [],
    }
  },
  setup() {
    const { getTopBCPs } = useTopBCP()
    const { statuses } = useBCPStatusEnum()

    return {
      getTopBCPs,
      statuses,
    }
  },
  mounted() {
    this.fetchTopBCPs()
  },
  methods: {
    /** Fetch top BCP */
    async fetchTopBCPs() {
      this.isLoading = true

      const data = await this.getTopBCPs()

      if (data) {
        this.bcps = data
        this.isLoading = false
      }
    },

    /**
     *
     * @param {*} idx
     * @param {*} bia
     */
    handleShowContextMenu(idx, bcp) {
      bcp.statusObj = this.getStatus(bcp)
      this.$refs.contextMenu.bcp = bcp
      this.$refs.contextMenu.position = ++idx
      this.$refs.contextMenu.isOpen = true
    },

    /**
     *
     * @param {*} value
     */
    getStatus(bcp) {
      return (
        this.statuses.find((item) => {
          return item?.value === bcp?.status
        }) || { name: "Unknown" }
      )
    },
  },
  components: { SummaryRowSkeleton, BCPContextMenu },
}
</script>
