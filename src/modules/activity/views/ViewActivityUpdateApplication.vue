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
      <!-- Applications -->
      <EcBox class="w-full mb-8">
        <EcFlex class="items-center">
          <EcLabel class="text-base font-medium"> {{ $t("activity.labels.software") }}</EcLabel>

          <!-- Add button -->
          <EcButton
            v-if="form.applications.length < applications.length"
            class="ml-2"
            variant="primary-rounded"
            @click="handleAddMoreApplication"
            v-tooltip="{ text: 'More application/software' }"
          >
            <EcIcon icon="Plus" width="16" height="16" />
          </EcButton>
        </EcFlex>

        <!-- remote access row -->
        <EcBox class="items-center mb-2 w-full" v-for="(app, index) in form.applications" :key="index">
          <EcFlex class="items-center">
            <RFormInput
              class="w-full sm:w-6/12 sm:pr-6"
              v-model="form.applications[index].uid"
              componentName="EcSelect"
              :options="filteredApplications"
              :valueKey="'uid'"
              :validator="v$"
              field="form.applications[index].uid"
            />

            <!-- Loading software -->
            <EcSpinner v-if="isLoadingSoftwares" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.applications.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveApplication(index)"
            >
              <EcIcon class="text-c1-400" icon="X" width="16" height="16" />
            </EcButton>

            <!-- End app access select -->
          </EcFlex>

          <!-- Error message -->
          <EcBox v-if="v$.form.applications.$errors?.length > 0">
            <EcText
              class="text-base text-cError-500 text-left"
              v-for="error in v$.form.applications.$each.$response.$errors[index].uid"
              :key="error"
            >
              {{ error.$message }}
            </EcText>
          </EcBox>
          <!-- Add new app slot -->
        </EcBox>
      </EcBox>
      <!-- End add more application -->

      <!-- IT data and storage -->
      <EcBox>
        <EcLabel class="text-base font-medium mb-3">{{ $t("activity.labels.dataStorage") }}</EcLabel>

        <EcFlex class="flex-wrap max-w-full mb-8">
          <EcBox class="w-full sm:w-4/12 sm:pr-6">
            <RFormInput
              v-model="form.it_solution.data"
              componentName="EcInputText"
              :label="$t('activity.labels.data')"
              :validator="v$"
              field="form.it_solution.data"
              @input="v$.form.it_solution.data.$touch()"
            />
          </EcBox>

          <EcBox class="w-full sm:w-4/12 sm:pr-6">
            <RFormInput
              v-model="form.it_solution.location"
              componentName="EcInputText"
              :label="$t('activity.labels.storageLocation')"
              :validator="v$"
              field="form.it_solution.location"
              @input="v$.form.it_solution.location.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>

      <!-- Equipments -->
      <EcBox class="w-full mb-8">
        <EcFlex class="items-center">
          <EcLabel class="text-base font-medium"> {{ $t("activity.labels.equipments") }}</EcLabel>
          <!-- Add button -->
          <EcButton
            v-if="form.equipments.length < equipments.length"
            class="ml-2"
            variant="primary-rounded"
            @click="handleAddMoreEquipment"
            v-tooltip="{ text: 'More equipment' }"
          >
            <EcIcon icon="Plus" width="16" height="16" />
          </EcButton>
        </EcFlex>

        <!-- remote access row -->
        <EcBox class="items-center mb-2 w-full" v-for="(equipment, index) in form.equipments" :key="index">
          <EcFlex class="items-center">
            <RFormInput
              class="w-full sm:w-6/12 sm:pr-6"
              v-model="form.equipments[index].uid"
              componentName="EcSelect"
              :options="filteredEquipments"
              :valueKey="'uid'"
              :validator="v$"
              field="form.equipments[index].uid"
            />

            <!-- Loading software -->
            <EcSpinner v-if="isLoadingEquipments" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="form.equipments.length > 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveEquipment(index)"
            >
              <EcIcon class="text-c1-400" icon="X" width="16" height="16" />
            </EcButton>

            <!-- End app access select -->
          </EcFlex>

          <!-- Error message -->
          <EcBox v-if="v$.form.equipments.$errors?.length > 0">
            <EcText
              class="text-base text-cError-500 text-left"
              v-for="error in v$.form.equipments.$each.$response.$errors[index].uid"
              :key="error"
            >
              {{ error.$message }}
            </EcText>
          </EcBox>
          <!-- Add new equipment slot -->
        </EcBox>
      </EcBox>
      <!-- End Equipments -->
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
import { ref } from "vue"
import { useApplications } from "@/readybc/composables/use/useApplications"
import { useEquipments } from "@/readybc/composables/use/useEquipments"
import { useActivityApplicationsAndEquipments } from "../use/useActivityApplicationsAndEquipments"

import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"
import { useActivityDetail } from "../use/useActivityDetail"
import EcFlex from "@/components/EcFlex/index.vue"

export default {
  name: "ViewActivityApplication",
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
    const { form, v$, updateApplicationAnEquipments } = useActivityApplicationsAndEquipments()
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
     * Filter software
     */
    filteredApplications() {
      return this.applications.map((app) => {
        app.disabled = this.form.applications.includes(app.uid)

        return app
      })
    },

    /**
     * Filter equipments
     */
    filteredEquipments() {
      return this.equipments.map((equipment) => {
        equipment.disabled = this.form.equipments.includes(equipment.uid)

        return equipment
      })
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
        setTimeout(this.redirectToActivityTolerant, 1000)
      }
      this.isLoading = false
    },

    /**
     * Redirect to activity list
     */
    redirectToActivityList() {
      goto("ViewActivityList")
    },

    /**
     * Redirect to activity tolerant
     */
    redirectToActivityTolerant() {
      goto("ViewActivityUpdateTolerant")
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
     * Back to Activity list
     */
    handleClickBack() {
      const { uid } = this.$route.params
      goto("ViewActivityUpdateRemoteAccess", {
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

    // ======== Pre-load =======//

    /**
     * Fetch Activity
     */
    async fetchActivity() {
      const { uid } = this.$route.params

      this.isLoading = true

      const response = await this.getActivity(uid, ["applications", "itSolution", "equipments"])

      if (response && response.uid) {
        this.transformFormData(response)
      }

      this.isLoading = false
    },

    /**
     * Transform data
     */
    transformFormData(response) {
      // IT Solution

      if (response.it_solution) {
        this.form.it_solution = response.it_solution
      }

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
  components: { ModalCancelAddActivity, EcFlex },
}
</script>
