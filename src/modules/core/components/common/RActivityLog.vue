<template>
  <EcBox class="w-full">
    <!-- Menu -->
    <EcFlex class="items-center w-full">
      <EcLabel class="w-5/6">{{ $t("bia.labels.activity") }}</EcLabel>

      <RTableAction class="">
        <!-- View History -->
        <EcFlex class="items-center px-4 py-2 cursor-pointer" @click="handleChangeActivityCategory('histories')">
          <EcText class="font-medium">History</EcText>
        </EcFlex>

        <!-- View Comments -->
        <EcFlex class="items-center px-4 py-2 cursor-pointer" @click="handleChangeActivityCategory('comments')">
          <EcText class="font-medium text-right">Comments</EcText>
        </EcFlex>

        <!-- View Newest -->
        <EcFlex class="items-center px-4 py-2 cursor-pointer">
          <EcText class="font-medium text-right">Newest</EcText>
        </EcFlex>
      </RTableAction>
    </EcFlex>

    <!-- Activities Comments/Logs -->
    <EcBox v-if="!isLoading" class="mt-4 max-h-64 overflow-x-auto">
      <!-- Comments - Comment Row-->
      <EcBox v-if="isCurrentComments">
        <EcLabel class="mt-1 mb-4 text-sm">Comments</EcLabel>
        <RCommentRow></RCommentRow>
      </EcBox>

      <!-- Histories - Activity Row -->
      <EcBox v-if="isCurrentHistories">
        <EcLabel class="mt-1 mb-4 text-sm">Histories</EcLabel>
        <EcBox v-for="(log, idx) in logs" :key="idx">
          <RActivityLogRow :log="log"></RActivityLogRow>
        </EcBox>
      </EcBox>

      <!-- End Activity Row -->
    </EcBox>

    <EcSpinner v-else />
  </EcBox>
</template>

<script>
import { useGlobalStore } from "@/stores/global"
import { useBIALog } from "@/modules/assessment/use/useBIALog"

export default {
  name: "RActivityLog",
  props: {
    uid: {
      type: String,
      default: "",
    },
    fetcher: {
      type: Function,
      default: (uid) => {},
    },
  },
  data() {
    return {
      isLoading: false,
      logs: [],
      currentActivity: "histories",
    }
  },
  setup(props) {
    const globalStore = useGlobalStore()
    const { getBIALogs } = useBIALog()
    return {
      globalStore,
      getBIALogs,
    }
  },
  mounted() {
    this.fetchLogs()
  },
  computed: {
    isCurrentComments() {
      return this.currentActivity === "comments"
    },
    isCurrentHistories() {
      return this.currentActivity === "histories"
    },
  },
  methods: {
    /**
     *
     */
    async fetchLogs() {
      this.isLoading = true
      const res = await this.fetcher(this.uid)
      if (res?.data) {
        this.logs = res.data
      }
      this.isLoading = false
    },

    /**
     *
     * @param {*} category
     */
    handleChangeActivityCategory(category) {
      this.currentActivity = category
    },
  },
  components: {},
}
</script>
