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
        <EcText class="w-11/12 font-bold text-lg mb-4">{{ $t("activity.title.software") }}</EcText>
        <EcButton class="mx-auto mr-0 my-auto mt-0" variant="tertiary-rounded" title="Cancel" @click="handleOpenCancelModal">
          <EcIcon class="text-sm text-cError-500" icon="X" />
        </EcButton>
      </EcFlex>

      <!-- Applications -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full">
          <EcLabel class="text-sm"> {{ $t("activity.labels.software") }}</EcLabel>

          <!-- Softwares select -->
          <EcFlex class="items-center mb-2 w-full" v-for="(role, index) in form.applications" :key="index">
            <EcBox class="w-full sm:w-6/12 sm:pr-6">
              <RFormInput
                v-model="form.applications[index]"
                componentName="EcSelect"
                :options="filteredApplications"
                :valueKey="'uid'"
                :validator="v$"
                field="form.applications[index]"
              />
              <!-- Add new Software slot -->
            </EcBox>

            <!-- Loading software -->
            <EcSpinner v-if="isLoadingSoftwares" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.applications?.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveApplication(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add button -->
            <EcButton
              v-if="index == form.applications.length - 1 && form.applications.length < applications.length"
              class="ml-2"
              variant="primary-rounded"
              @click="handleAddMoreApplication"
            >
              <EcIcon icon="Plus" />
            </EcButton>
          </EcFlex>
          <!-- End Applications select -->
        </EcBox>

        <!-- End Softwares select -->
      </EcFlex>
      <!-- End add more role -->

      <!-- IT data and storage -->
      <EcBox>
        <EcLabel class="text-sm mb-3">{{ $t("activity.labels.dataStorage") }}</EcLabel>

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
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full">
          <EcLabel class="text-sm"> {{ $t("activity.labels.equipments") }}</EcLabel>

          <!-- Equipments select -->
          <EcFlex class="items-center mb-2 w-full" v-for="(role, index) in form.equipments" :key="index">
            <EcBox class="w-full sm:w-6/12 sm:pr-6">
              <RFormInput
                v-model="form.equipments[index]"
                componentName="EcSelect"
                :options="filteredEquipments"
                :valueKey="'uid'"
                :validator="v$"
                field="form.equipments[index]"
              />
            </EcBox>

            <!-- Loading Equipment -->
            <EcSpinner v-if="isLoadingEquipments" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.equipments.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveEquipment(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add new Equipment slot -->
            <EcButton
              v-if="index == form.equipments.length - 1 && form.equipments.length < equipments.length"
              class="ml-2"
              variant="primary-rounded"
              @click="handleAddMoreEquipment"
            >
              <EcIcon icon="Plus" />
            </EcButton>
          </EcFlex>
          <!-- End Equipment select -->
        </EcBox>

        <!-- End Equipments  -->
      </EcFlex>
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
import { useActivityApplicationsAndEquipments } from "../use/useActivityApplicationsAndEquipments"

import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"
import { useActivityDetail } from "../use/useActivityDetail"

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

    return {
      form,
      v$,
      applications,
      equipments,
      getActivity,
      getApplications,
      getEquipments,
      updateApplicationAnEquipments,
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

      const response = await this.updateApplicationAnEquipments(this.form, uid)

      if (response && response.uid) {
        setTimeout(this.redirectToActivityList, 2000)
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
      this.form.applications.push("")
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
      this.form.equipments.push("")
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
      goto("ViewActivityRemoteAccess")
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

      const response = await this.getActivity(uid)

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
      debugger
      if (response.it_solution) {
        this.form.it_solution = response.it_solution
      }

      // Applications
      if (response.applications.length > 0) {
        this.form.applications = response.applications.map((app) => {
          return app.uid
        })
      }

      // Equipments
      if (response.equipments.length > 0) {
        this.form.equipments = response.equipments.map((equipment) => {
          return equipment.uid
        })
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
  components: { ModalCancelAddActivity },
}
</script>
