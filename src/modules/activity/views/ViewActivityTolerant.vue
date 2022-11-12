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
        <EcText class="w-11/12 font-bold text-lg mb-4">{{ $t("activity.title.tolerant") }}</EcText>
        <EcButton
          class="mx-auto mr-0 my-auto mt-0"
          variant="tertiary-rounded"
          v-tooltip="{ text: $t('activity.tooltips.cancel') }"
          @click="handleOpenCancelModal"
        >
          <EcIcon class="text-base text-cError-500" icon="X" />
        </EcButton>
      </EcFlex>

      <!-- Time -->
      <EcBox class="w-11/12 mb-8">
        <EcFlex>
          <EcLabel class="text-base font-medium mr-1" v-tooltip="{ text: $t('activity.tooltips.tolerantTime') }">
            {{ $t("activity.tolerant.time") }}
          </EcLabel>
          <EcIcon icon="QuestionMark" width="16" />
        </EcFlex>

        <!-- Slider -->
        <EcOptionSlider
          v-if="!isLoadingMTDPTimeOptions"
          class="mt-10"
          v-model="form.tolerable_period_disruptions"
          :options="filteredTimeOptions"
          valueKey="uid"
        />
        <EcSpinner v-else />
      </EcBox>
      <!-- End add more application -->

      <!-- dependent_time-->
      <EcBox class="mb-8">
        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.dependent_time"
            :label="$t('activity.tolerant.timeOf')"
            :rows="2"
            componentName="EcInputLongText"
            showLabel
            :validator="v$"
            field="form.dependent_time"
            @input="v$.form.dependent_time.$touch()"
          />
        </EcFlex>
      </EcBox>
      <!-- Time of -->

      <!-- Time of-->
      <EcBox class="mb-8">
        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md mb-8">
          <RFormInput
            v-model="form.reason_choose_dependent_time"
            :label="$t('activity.tolerant.reason')"
            :rows="2"
            componentName="EcInputLongText"
            showLabel
            :validator="v$"
            field="form.reason_choose_dependent_time"
            @input="v$.form.reason_choose_dependent_time.$touch()"
          />
        </EcFlex>
      </EcBox>
      <!-- Time of -->

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
          {{ $t("activity.buttons.next") }}
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
import { useActivityTolerant } from "../use/useActivityTolerant"
import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"
import { useActivityDetail } from "../use/useActivityDetail"

export default {
  name: "ViewActivityTolerant",
  data() {
    return {
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingMTDPTimeOptions: false,
    }
  },
  setup() {
    // PRE LOAD

    // Functions
    const { form, v$, mtdpTimeOptions, getMTDPTimeOptions, updateActivityMTDP } = useActivityTolerant()
    const { getActivity } = useActivityDetail()

    const STEP_APPLICATION = 4

    return {
      form,
      v$,
      mtdpTimeOptions,
      getActivity,
      getMTDPTimeOptions,
      updateActivityMTDP,
      STEP_APPLICATION,
    }
  },

  mounted() {
    this.fetchActivity()
    this.fetchMTDPTimeOptions()
  },

  computed: {
    /**
     * Filter Time Options
     */
    filteredTimeOptions() {
      return this.mtdpTimeOptions
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

      const payload = {
        tolerable_period_disruptions: [
          {
            uid: this.form.tolerable_period_disruptions?.uid,
            dependent_time: this.form.dependent_time,
            reason_choose_dependent_time: this.form.reason_choose_dependent_time,
          },
        ],
      }
      const response = await this.updateActivityMTDP(payload, uid)

      if (response && response.uid) {
        setTimeout(this.redirectToActivityRTO, 1000)
      }
      this.isLoading = false
    },

    /**
     * Redirect to activity list
     */
    redirectToActivityRTO() {
      goto("ViewActivityRTO")
    },

    /**
     * Back to Activity list
     */
    handleClickBack() {
      goto("ViewActivityApplication")
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

      const response = await this.getActivity(uid, ["tolerablePeriodDisruptions"])

      if (response && response.uid) {
        this.transformFormData(response)
      }

      this.isLoading = false
    },

    /**
     * Transform data
     */
    transformFormData(response) {
      const tpd = response?.tolerable_period_disruptions[0]

      if (tpd) {
        this.form.tolerable_period_disruptions = {
          uid: tpd?.uid,
          name: tpd?.name,
        }

        this.form.dependent_time = tpd?.pivot?.dependent_time
        this.form.reason_choose_dependent_time = tpd?.pivot?.reason_choose_dependent_time
      }
    },

    /**
     * Time MTDP Options
     */
    async fetchMTDPTimeOptions() {
      this.isLoadingMTDPTimeOptions = true
      const response = await this.getMTDPTimeOptions()

      if (response) {
        this.mtdpTimeOptions = response
      }

      this.isLoadingMTDPTimeOptions = false
    },
  },
  components: { ModalCancelAddActivity },
}
</script>
