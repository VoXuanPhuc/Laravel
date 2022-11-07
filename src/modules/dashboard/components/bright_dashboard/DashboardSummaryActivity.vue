<template>
  <!-- Activities  -->
  <EcBox class="w-full md:w-10/12 mt-8">
    <EcLabel class="font-semibold mt-6">List of Critical Business Activities</EcLabel>

    <!-- Activities -->
    <EcBox v-if="!isLoading">
      <EcFlex v-for="(activity, idx) in activities" :key="activity.uid" class="items-center mb-2">
        <!-- Activity Row -->
        <RFormInput
          v-model="activities[idx].name"
          iconPrefix="DActivityDoc"
          variant="primary-dashboard"
          :readonly="true"
          componentName="EcInputText"
          @click="handleClickActivity(activity)"
          class="hover:cursor-pointer"
        />
      </EcFlex>
    </EcBox>

    <!-- Skeleton -->
    <EcBox v-else>
      <EcFlex v-for="item in 2" :key="item" class="items-center mb-2">
        <!-- Activity Row -->
        <div class="flex items-center input w-full bg-c4-50 h-10 rounded-lg skeleton mt-1 mb-2 py-2">
          <div class="w-6 h-6 ml-6 rounded bg-c4-50"></div>
          <div class="w-10/12 h-4 ml-6 rounded bg-c4-50"></div>
        </div>
      </EcFlex>
    </EcBox>

    <EcFlex class="mt-6 text-base text-c3-100 justify-end hover:cursor-pointer text" @click="handleClickSeeAll">See All</EcFlex>
  </EcBox>
</template>

<script>
import { goto } from "@/modules/core/composables"
import { useTopActivity } from "../../use/useTopActivity"

export default {
  name: "DashboardSummaryActivity",

  data() {
    return {
      isLoading: true,
      activities: [],
    }
  },

  setup() {
    const { getTopActivities } = useTopActivity()

    return {
      getTopActivities,
    }
  },

  mounted() {
    this.fetchTopActivities()
  },
  methods: {
    /**
     * See All
     */
    handleClickSeeAll() {
      goto("ViewActivityList")
    },

    handleClickActivity(activity) {
      goto("ViewActivityDetail", {
        params: {
          uid: activity?.uid,
        },
      })
    },

    /** Fetch top Activities */
    async fetchTopActivities() {
      this.isLoading = true

      const data = await this.getTopActivities()

      if (data) {
        this.activities = data
        this.isLoading = false
      }
    },
  },
}
</script>
