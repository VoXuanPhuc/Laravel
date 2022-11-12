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
        <EcOptionSlider class="mt-10" v-model="form.period" :options="filteredTimeOptions" />
      </EcBox>
      <!-- End add more application -->

      <!-- Tested Real Time-->
      <EcBox class="mb-8">
        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.tested_realtime"
            :label="$t('activity.rto.testedRealTime')"
            componentName="EcToggle"
            showLabel
            :labelTrue="$t('activity.labels.yes')"
            :labelFalse="$t('activity.labels.no')"
          />
        </EcFlex>
      </EcBox>
      <!-- Tested Real time -->

      <!-- Scenario-->
      <EcBox class="mb-8">
        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.scenario"
            :label="$t('activity.rto.scenario')"
            componentName="EcMultiSelect"
            :options="filteredScenarios"
            :isSingleSelect="true"
          />
        </EcFlex>
      </EcBox>
      <!-- Scenario -->

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
import { ref } from "vue"
import { useApplications } from "@/readybc/composables/use/useApplications"
import { useEquipments } from "@/readybc/composables/use/useEquipments"
import { useActivityTolerant } from "../use/useActivityTolerant"

import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"
import { useActivityDetail } from "../use/useActivityDetail"
import EcFlex from "@/components/EcFlex/index.vue"
import EcLabel from "@/components/EcLabel/index.vue"

export default {
  name: "ViewActivityTolerant",
  data() {
    return {
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingSoftwares: false,
      isLoadingEquipments: false,
    }
  },
  setup() {
    // PRE LOAD

    const { getApplications } = useApplications()
    const { getEquipments } = useEquipments()

    // Functions
    const { form, v$, updateApplicationAnEquipments } = useActivityTolerant()
    const { getActivity } = useActivityDetail()

    const applications = ref([])
    const equipments = ref([])
    const STEP_APPLICATION = 3

    return {
      form,
      v$,
      applications,
      equipments,
      getActivity,
      getApplications,
      getEquipments,
      updateApplicationAnEquipments,
      STEP_APPLICATION,
    }
  },

  mounted() {
    this.fetchActivity()
    this.fetchApplications()
    this.fetchEquipments()
  },

  computed: {
    /**
     * Filter Scenarios
     */
    filteredScenarios() {
      return [
        { name: "Loss of Personnel", value: "1" },
        { name: "Loss of IT Systems", value: "2" },
        { name: "Denial of Access to Site", value: "3" },
        { name: "Loss of Utilities or Equipment", value: "4" },
        { name: "Critical Supplier Disruption", value: "4" },
      ]
    },

    /**
     * Filter Time Options
     */
    filteredTimeOptions() {
      return [
        { name: "Immediate (<2 hours)", value: "im" },
        { name: "2 Hours", value: "2" },
        { name: "4 Hours", value: "4" },
        { name: "6 Hours", value: "6" },
        { name: "8 Hours", value: "8" },
        { name: "12 Hours", value: "8" },
        { name: "24 Hours", value: "8" },
        { name: "2 Days", value: "8" },
        { name: "3 Days", value: "8" },
        { name: "1 Week", value: "8" },
        { name: "2 Weeks", value: "8" },
        { name: "4 Weeks", value: "8" },
        { name: "> 4 Weeks", value: "8" },
      ]
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

      const response = await this.updateApplicationAnEquipments(this.form, uid)

      if (response && response.uid) {
        setTimeout(this.redirectToActivityList, 1000)
      }
      this.isLoading = false
    },

    /**
     * Redirect to activity list
     */
    redirectToActivityList() {
      goto("ViewActivityList")
    },

    // =========== APPLICATIONS ================ //
    /**
     * Add more alternative role
     */
    handleAddMoreApplication() {
      this.form.applications.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveApplication(index) {
      this.form.applications.splice(index, 1)
    },

    // =========== EQUIPMENTS ================ //

    /**
     * Add equipment
     */
    handleAddMoreEquipment() {
      this.form.equipments.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveEquipment(index) {
      this.form.equipments.splice(index, 1)
    },
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

      const response = await this.getActivity(uid, ["applications", "equipments"])

      if (response && response.uid) {
        this.transformFormData(response)
      }

      this.isLoading = false
    },

    /**
     * Transform data
     */
    transformFormData(response) {
      // Applications
      if (response.applications.length > 0) {
        this.form.applications = response.applications
      }

      // Equipments
      if (response.equipments.length > 0) {
        this.form.equipments = response.equipments
      }
    },

    /**
     * Applications
     */
    async fetchApplications() {
      this.isLoadingSoftwares = true
      const response = await this.getApplications()

      if (response && response.data) {
        this.applications = response.data
      }

      this.isLoadingSoftwares = false
    },

    /**
     * Equipments
     */
    async fetchEquipments() {
      this.isLoadingEquipments = true
      const response = await this.getEquipments()

      if (response && response.data) {
        this.equipments = response.data
      }

      this.isLoadingEquipments = false
    },
  },
  components: { ModalCancelAddActivity, EcFlex, EcLabel },
}
</script>
