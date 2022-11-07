<template>
  <!-- BIA  -->
  <EcBox class="w-full md:w-8/12 mt-8">
    <SummaryRowSkeleton :rows="2" :loading="isLoading">
      <EcBox class="relative">
        <EcFlex class="items-center mb-2" v-for="(bia, idx) in bias" :key="bia.uid">
          <!-- BIA Row -->
          <EcInputText
            v-model="bias[idx].name"
            variant="primary-dashboard"
            componentName="EcInputText"
            field="form.name"
            class="w-8/12 text-sm"
            :readonly="true"
            v-tooltip="{ text: 'Enter to save' }"
          />

          <EcBox class="relative ml-3 w-4/12">
            <EcButton
              variant="wrapper"
              :class="[getStatus(bia)?.tag_color, getStatus(bia)?.tag_text_color || 'text-cWhite']"
              class="text-base rounded-lg px-5 py-3 text-cBlack justify-center border-0"
              @click="handleShowContextMenu(idx, bia)"
            >
              {{ getStatus(bia)?.name }}
            </EcButton>
          </EcBox>

          <!-- Button Action -->
        </EcFlex>

        <!--Context menu -->
        <BIAContextMenu :statuses="statuses" ref="contextMenu"></BIAContextMenu>
      </EcBox>
    </SummaryRowSkeleton>
  </EcBox>
</template>

<script>
import { useTopBIA } from "../../use/useTopBIA"
import { useBIAStatusEnum } from "@/modules/assessment/use/useBIAStatusEnum"
import SummaryRowSkeleton from "./SummaryRowSkeleton.vue"
import { ref } from "vue"
import BIAContextMenu from "./BIAContextMenu.vue"

export default {
  name: "DashboardSummaryBIA",

  data() {
    return {
      isLoading: false,
      bias: ref([]),
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
     * @param {*} idx
     * @param {*} bia
     */
    handleShowContextMenu(idx, bia) {
      bia.statusObj = this.getStatus(bia)
      this.$refs.contextMenu.bia = bia
      this.$refs.contextMenu.position = ++idx
      this.$refs.contextMenu.isOpen = true
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
  components: { SummaryRowSkeleton, BIAContextMenu },
}
</script>
