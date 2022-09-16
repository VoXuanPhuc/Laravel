<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("activity.title.editActivity") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <!-- Remote factors -->
      <EcBox class="w-full mb-8">
        <EcLabel class="text-sm"> {{ $t("activity.labels.enableRemote") }}</EcLabel>

        <!-- remote access row -->
        <EcBox class="items-center mb-2 w-full" v-for="(remoteAccessFactor, index) in form.remote_access_factors" :key="index">
          <EcFlex class="items-center">
            <RFormInput
              class="w-full sm:w-6/12 sm:pr-6"
              v-model="form.remote_access_factors[index].uid"
              componentName="EcSelect"
              :options="filteredRemoteAccessFactors"
              :valueKey="'uid'"
              :validator="v$"
              field="form.remote_access_factors[index].uid"
            />

            <!-- Loading utilities -->
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
            <!-- End Remote access select -->
          </EcFlex>

          <!-- Error message -->
          <EcBox v-if="v$.form.remote_access_factors.$errors?.length > 0">
            <EcText
              class="text-sm text-cError-500 text-left"
              v-for="error in v$.form.remote_access_factors.$each.$response.$errors[index].uid"
              :key="error"
            >
              {{ error.$message }}
            </EcText>
          </EcBox>
          <!-- Add new remote access slot -->
        </EcBox>
      </EcBox>
      <!-- End add more remote access factors -->

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
      isUpdate: false,
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
    const STEP_REMOTE_ACCESSES = 2
    return {
      form,
      v$,
      getRemoteAccessFactors,
      updateActivityRemoteAccess,
      getActivity,
      STEP_REMOTE_ACCESSES,
    }
  },
  beforeMount() {
    const { isUpdate } = this.$route.params
    this.isUpdate = isUpdate
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
      const selectedRAFUids = this.form.remote_access_factors.map((raf) => {
        return raf.uid
      })

      return this.remoteAccessFactors.map((remoteAccessFactor) => {
        remoteAccessFactor.disabled = selectedRAFUids.includes(remoteAccessFactor.uid)

        return remoteAccessFactor
      })
    },
  },
  methods: {
    // =========== REMOTE ACCESS FACTORS ================ //
    /**
     * Add more alternative role
     */
    handleAddMoreRemoteAccessFactor() {
      this.form.remote_access_factors.push({ uid: "" })
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
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      const { uid } = this.$route.params

      this.isLoading = true
      this.form.step = this.STEP_REMOTE_ACCESSES

      const response = await this.updateActivityRemoteAccess(this.form, uid)
      this.isLoading = false

      if (response && response.uid) {
        goto("ViewActivityUpdateApplication", {
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
      const { uid } = this.$route.params
      goto("ViewActivityDetail", {
        params: {
          uid: uid,
        },
      })
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
        this.form.remote_access_factors = response.remote_access_factors
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
