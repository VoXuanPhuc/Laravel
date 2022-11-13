<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("activity.title.newActivity") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <!-- Title and cancel button -->
      <EcFlex>
        <EcText class="w-11/12 font-bold text-lg mb-4">{{ $t("activity.title.rto") }}</EcText>
        <EcButton
          class="mx-auto mr-0 my-auto mt-0"
          variant="tertiary-rounded"
          v-tooltip="{ text: $t('activity.tooltips.cancel') }"
          @click="handleOpenCancelModal"
        >
          <EcIcon class="text-base text-cError-500" icon="X" />
        </EcButton>
      </EcFlex>

      <!-- Period -->
      <EcBox class="w-11/12 mb-8">
        <EcFlex>
          <EcLabel class="text-base font-medium mr-1" v-tooltip="{ text: $t('activity.tooltips.rtoPeriod') }">
            {{ $t("activity.rto.period") }}
          </EcLabel>
          <EcIcon icon="QuestionMark" width="16" />
        </EcFlex>

        <!-- Slider -->
        <EcOptionSlider
          v-if="!isLoadingRecoveryTimes"
          class="mt-10"
          v-model="form.recoveryTime"
          :options="filteredRecoveryTimeOptions"
          valueKey="uid"
        />
        <EcSpinner v-else />
      </EcBox>
      <!-- End add more application -->

      <!-- Tested Real Time-->
      <EcBox class="mb-8">
        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.is_rto_tested"
            :label="$t('activity.rto.testedRealTime')"
            componentName="EcToggle"
            showLabel
            :labelTrue="$t('activity.labels.yes')"
            :labelFalse="$t('activity.labels.no')"
            :validator="v$"
            field="form.is_rto_tested"
          />
        </EcFlex>
      </EcBox>
      <!-- Tested Real time -->

      <!-- Scenario-->
      <EcBox class="mb-8">
        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.disruption_scenarios"
            :label="$t('activity.rto.scenario')"
            componentName="EcMultiSelect"
            :options="filteredDisruptionScenarios"
            :valueKey="'uid'"
            :validator="v$"
            field="form.disruption_scenarios"
          />
        </EcFlex>
      </EcBox>
      <!-- Scenario -->

      <!-- Existing workaround-->
      <EcBox
        v-for="(disruption, idx) in form.disruption_scenarios"
        :key="disruption.uid"
        class="ml-4 mb-8 border-b border-c0-200 border-dashed"
      >
        <!-- Workaround question-->
        <EcFlex class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.disruption_scenarios[idx].workaround_option"
            :label="$t('activity.rto.existingWorkaround', { disruptionName: disruption.name })"
            componentName="EcSelect"
            :allowSelectNothing="false"
            :options="filteredExistingWorkarounds"
          />
        </EcFlex>

        <!-- Workaround Solution -->
        <EcFlex v-if="form.disruption_scenarios[idx].workaround_option == EW_FREE_TEXT" class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.disruption_scenarios[idx].workaround_solution"
            :label="$t('activity.rto.workaroundSolution', { disruptionName: disruption.name })"
            componentName="EcInputText"
          />
        </EcFlex>

        <!-- Workaround Enacted -->
        <EcFlex v-if="form.disruption_scenarios[idx].workaround_option == EW_FREE_TEXT" class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.disruption_scenarios[idx].workaround_feasibly"
            :label="$t('activity.rto.workaroundEnacted')"
            componentName="EcInputText"
          />
        </EcFlex>
      </EcBox>
      <!-- Existing workaround-->
      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button back -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickBack">
          {{ $t("activity.buttons.back") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickNext">
          {{ $t("activity.buttons.finish") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
    <!-- End actions -->

    <!-- Modals -->
    <teleport to="#layer2">
      <ModalCancelAddActivity :isModalCancelOpen="isModalCancelOpen" @handleCloseCancelModal="handleCloseCancelModal" />
    </teleport>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useActivityRTO } from "../use/useActivityRTO"
import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"
import { useActivityDetail } from "../use/useActivityDetail"

export default {
  name: "ViewActivityRTO",
  data() {
    return {
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingRecoveryTimes: false,
      isLoadingDisruptionScenarios: false,
    }
  },
  setup() {
    // PRE LOAD

    // Functions
    const {
      EW_NONE_IDENTIFIED,
      EW_FREE_TEXT,
      form,
      v$,
      existingWorkarounds,
      recoveryTimeOptions,
      disruptionScenarios,
      getRecoveryTimeOptions,
      getDisruptionScenarios,
      updateActivityRTO,
    } = useActivityRTO()
    const { getActivity } = useActivityDetail()

    const STEP_APPLICATION = 5

    return {
      EW_NONE_IDENTIFIED,
      EW_FREE_TEXT,
      form,
      v$,
      existingWorkarounds,
      recoveryTimeOptions,
      disruptionScenarios,
      getActivity,
      getRecoveryTimeOptions,
      getDisruptionScenarios,
      updateActivityRTO,
      STEP_APPLICATION,
    }
  },

  mounted() {
    this.fetchActivity()
    this.fetchRecoveryTimeOptions()
    this.fetchDisruptionScenarios()
  },

  computed: {
    /**
     * Filter Scenarios
     */
    filteredDisruptionScenarios() {
      return this.disruptionScenarios
    },

    /**
     * Filter Time Options
     */
    filteredRecoveryTimeOptions() {
      return this.recoveryTimeOptions
    },

    /**
     * Filter Existing workarounds
     */
    filteredExistingWorkarounds() {
      return this.existingWorkarounds
    },
  },
  methods: {
    /**
     * Creaate Activity
     */
    async handleClickNext() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      const { uid } = this.$route.params
      this.isLoading = true

      this.form.step = this.STEP_APPLICATION

      const response = await this.updateActivityRTO(this.mappedPayload(), uid)

      if (response && response.uid) {
        setTimeout(this.redirectToActivityList, 1000)
      }
      this.isLoading = false
    },

    /**
     * Mapped payload
     */
    mappedPayload() {
      return {
        recovery_times: [
          {
            uid: this.form?.recoveryTime?.uid,
            is_rto_tested: this.form?.is_rto_tested,
          },
        ],

        disruption_scenarios: this.form?.disruption_scenarios,
      }
    },

    /**
     * Redirect to activity list
     */
    redirectToActivityList() {
      goto("ViewActivityList")
    },

    // =========== Recovery times ================ //

    /**
     * Back to Activity Tolerant
     */
    handleClickBack() {
      goto("ViewActivityTolerant")
    },

    /**
     * Open cancel modal
     */
    handleOpenCancelModal() {
      this.isModalCancelOpen = true
    },

    /**
     * Open cancel modal
     */
    handleCloseCancelModal() {
      this.isModalCancelOpen = false
    },

    // ======== Pre-load =======//

    /**
     * Fetch Activity
     */
    async fetchActivity() {
      const { uid } = this.$route.params

      this.isLoading = true

      const response = await this.getActivity(uid, ["recoveryTimes", "disruptionScenarios"])

      if (response && response.uid) {
        this.transformFormData(response)
      }

      this.isLoading = false
    },

    /**
     * Transform data
     */
    transformFormData(response) {
      // recovery_times
      const recoveryTime = response.recovery_times[0]

      if (recoveryTime) {
        this.form.recoveryTime = {
          uid: recoveryTime?.uid,
          name: recoveryTime?.name,
        }

        this.form.is_rto_tested = recoveryTime?.pivot?.is_rto_tested
      }

      // disruption_scenarios
      if (response.disruption_scenarios) {
        this.form.disruption_scenarios = response.disruption_scenarios?.map((item) => {
          return {
            uid: item?.uid,
            name: item?.name,
            workaround_option: item?.pivot?.workaround_solution?.length > 1 ? this.EW_FREE_TEXT : this.EW_NONE_IDENTIFIED,
            workaround_solution: item?.pivot?.workaround_solution,
            workaround_feasibly: item?.pivot?.workaround_feasibly,
          }
        })
      }
    },

    /**
     * Recovery Times
     */
    async fetchRecoveryTimeOptions() {
      this.isLoadingRecoveryTimes = true
      const response = await this.getRecoveryTimeOptions()

      if (response) {
        this.recoveryTimeOptions = response
      }

      this.isLoadingRecoveryTimes = false
    },

    /**
     * Disruption Scenarios
     */
    async fetchDisruptionScenarios() {
      this.isLoadingDisruptionScenarios = true
      const response = await this.getDisruptionScenarios()

      if (response) {
        this.disruptionScenarios = response
      }

      this.isLoadingDisruptionScenarios = false
    },
  },
  components: { ModalCancelAddActivity },
}
</script>
