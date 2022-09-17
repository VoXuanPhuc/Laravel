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
        <EcText class="w-11/12 font-bold text-lg mb-4">{{ $t("activity.title.remote") }}</EcText>
        <EcButton class="mx-auto mr-0 my-auto mt-0" variant="tertiary-rounded" title="Cancel" @click="handleOpenCancelModal">
          <EcIcon class="text-sm text-cError-500" icon="X" />
        </EcButton>
      </EcFlex>

      <!-- Remote factors -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full">
          <EcLabel class="text-sm"> {{ $t("activity.labels.enableRemote") }}</EcLabel>

          <!-- Remote access factor select -->
          <EcFlex class="items-center mb-2 w-full" v-for="(role, index) in form.remote_access_factors" :key="index">
            <EcBox class="w-full sm:w-6/12 sm:pr-6">
              <RFormInput
                v-model="form.remote_access_factors[index]"
                componentName="EcSelect"
                :options="filteredRemoteAccessFactors"
                :valueKey="'uid'"
                :validator="v$"
                field="form.remote_access_factors[index]"
              />
              <!-- Add new remote access factors slot -->
            </EcBox>

            <!-- Loading remote access factor -->
            <EcSpinner v-if="isLoadingRemoteAccessFactors" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.remote_access_factors.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveRemoteAccessFactor(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add button -->
            <EcButton
              v-if="
                index == form.remote_access_factors.length - 1 && form.remote_access_factors.length < remoteAccessFactors.length
              "
              class="ml-2"
              variant="primary-rounded"
              @click="handleAddMoreRemoteAccessFactor"
            >
              <EcIcon icon="Plus" />
            </EcButton>
          </EcFlex>
          <!-- End role select -->
        </EcBox>

        <!-- Add more roles -->
      </EcFlex>
      <!-- End add more role -->

      <!-- Unable to enable remote -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.on_site_requires"
            componentName="EcInputText"
            :label="$t('activity.labels.unableEnableRemote')"
            :validator="v$"
            field="form.unable_enable_remote"
            @input="v$.form.on_site_requires.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
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
import { useRemoteAccessFactors } from "@/readybc/composables/use/useRemoteAccessFactors"
import { useActivityRemoteAccessFactors } from "../use/useActivityRemoteAccessFactors"
import { useActivityDetail } from "../use/useActivityDetail"
import EcButton from "@/components/EcButton/index.vue"
import EcIcon from "@/components/EcIcon/index.vue"
import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"

export default {
  name: "ViewActivityRemoteAccess",
  data() {
    return {
      activityUid: null,
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingRemoteAccessFactors: false,
      remoteAccessFactors: [],
    }
  },
  setup() {
    // PRE-LOAD
    const { getRemoteAccessFactors } = useRemoteAccessFactors()

    const { form, v$, updateActivityRemoteAccess } = useActivityRemoteAccessFactors()
    const { getActivity } = useActivityDetail()

    return {
      form,
      v$,
      getRemoteAccessFactors,
      updateActivityRemoteAccess,
      getActivity,
    }
  },
  mounted() {
    this.fetchActivity()
    this.fetchRemoteAccessFactors()
  },
  computed: {
    /**
     * Filter remote access factor
     */
    filteredRemoteAccessFactors() {
      return this.remoteAccessFactors.map((remoteAccessFactor) => {
        remoteAccessFactor.disabled = this.form.remote_access_factors.includes(remoteAccessFactor.uid)

        return remoteAccessFactor
      })
    },
  },
  methods: {
    /**
     * Creaate Activity
     */
    async handleClickCreate() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }
      this.isLoading = true
      // const response = await this.createActivity(this.form)
      this.isLoading = false
    },

    // =========== REMOTE ACCESS FACTORS ================ //
    /**
     * Add more alternative role
     */
    handleAddMoreRemoteAccessFactor() {
      this.form.remote_access_factors.push("")
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveRemoteAccessFactor(index) {
      this.form.remote_access_factors.splice(index, 1)
    },

    /**
     * Next to step 3
     */
    async handleClickNext() {
      // this.v$.$touch()
      // if (this.v$.$invalid) {
      //   return
      // }

      const { uid } = this.$route.params

      this.isLoading = true

      const response = await this.updateActivityRemoteAccess(this.form, uid)
      this.isLoading = false

      if (response && response.uid) {
        goto("ViewActivityApplication", {
          params: {
            uid: response.uid,
          },
        })
      }
    },

    /**
     * Back to Activity list
     */
    handleClickBack() {
      goto("ViewActivityNew")
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

    // ======== PRE-LOAD ======/
    /**
     * Fetch activity
     */
    async fetchActivity() {
      const { uid } = this.$route.params

      this.isLoading = true

      const response = await this.getActivity(uid)

      if (response && response.uid) {
        this.transformFormData(response)
      }

      this.isLoading = false
    },

    /**
     * Transform data to form
     * @param {*} response
     */
    transformFormData(response) {
      this.form.on_site_requires = response.on_site_requires

      if (response.remote_access_factors.length > 0) {
        this.form.remote_access_factors = response.remote_access_factors.map((remoteAccessFactor) => {
          return remoteAccessFactor.uid
        })
      }
    },

    /**
     * Fetch remote access factors
     */
    async fetchRemoteAccessFactors() {
      this.isLoadingRemoteAccessFactors = true

      const response = await this.getRemoteAccessFactors()

      if (response && response.data) {
        this.remoteAccessFactors = response.data
      }

      this.isLoadingRemoteAccessFactors = false
    },
  },
  components: { EcButton, EcIcon, ModalCancelAddActivity },
}
</script>
