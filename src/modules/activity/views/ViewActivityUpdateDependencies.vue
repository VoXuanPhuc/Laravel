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
      <!-- Title and cancel button -->
      <EcFlex>
        <EcText class="w-11/12 font-bold text-lg mb-4">{{ $t("activity.title.dependencies") }}</EcText>
      </EcFlex>

      <!-- dependencies -->
      <EcBox class="w-full mb-8">
        <EcFlex class="items-center">
          <EcLabel class="text-base font-medium"> {{ $t("activity.labels.dependencies") }}</EcLabel>
          <!-- Add button -->
          <EcButton
            v-if="!isRefreshDependency"
            class="ml-2"
            variant="primary-rounded"
            @click="handleAddMoreDependency"
            v-tooltip="{ text: $t('activity.tooltips.addDependency') }"
          >
            <EcIcon icon="Plus" width="16" height="16" />
          </EcButton>
          <!-- refresh -->
          <EcButton
            v-else
            class="ml-2"
            variant="primary-rounded"
            @click="fetchDependencies"
            v-tooltip="{ text: $t('activity.tooltips.dependencyReload') }"
          >
            <EcIcon icon="Refresh" width="16" height="16" />
          </EcButton>
        </EcFlex>

        <!-- dependencies -->
        <EcFlex class="max-w-md items-center mb-8">
          <EcBox class="w-full sm:w-12/20 sm:pr-6">
            <RFormInput
              v-model="form.dependency_scenarios"
              componentName="EcMultiSelect"
              :options="dependencies"
              :valueKey="'uid'"
              :validator="v$"
              field="form.dependency_scenarios"
            />
          </EcBox>
          <EcSpinner class="my-auto mb-1" v-if="isLoadingDependencies"></EcSpinner>
        </EcFlex>
      </EcBox>
      <!-- End add more dependencies -->

      <!-- suppliers -->
      <EcBox class="w-full mb-8">
        <EcFlex>
          <EcLabel class="text-base font-medium"> {{ $t("activity.labels.suppliers") }}</EcLabel>

          <!-- Add button -->
          <EcButton
            v-if="!isRefreshSupplier"
            class="ml-2"
            variant="primary-rounded"
            @click="handleAddMoreSupplier"
            v-tooltip="{ text: $t('activity.tooltips.addSupplier') }"
          >
            <EcIcon icon="Plus" width="16" height="16" />
          </EcButton>
          <!-- refresh -->
          <EcButton
            v-else
            class="ml-2"
            variant="primary-rounded"
            @click="fetchSuppliers"
            v-tooltip="{ text: $t('activity.tooltips.supplierReload') }"
          >
            <EcIcon icon="Refresh" width="16" height="16" />
          </EcButton>
        </EcFlex>

        <EcFlex class="max-w-md items-center mb-8">
          <EcBox class="w-full sm:w-12/20 sm:pr-6">
            <RFormInput
              v-model="form.suppliers"
              componentName="EcMultiSelect"
              :options="suppliers"
              :valueKey="'uid'"
              :validator="v$"
              field="form.suppliers"
            />
          </EcBox>
          <EcSpinner class="my-auto mb-1" v-if="isLoadingSuppliers"></EcSpinner>
        </EcFlex>
      </EcBox>
      <!-- End suppliers -->

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
import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"
import { useActivityDetail } from "../use/useActivityDetail"
import { useDependencyList } from "@/modules/dependency/use/dependency/useDependencyList"
import { useSupplierList } from "@/modules/supplier/use/supplier/useSupplierList"
import { useDependenciesAndSuppliers } from "@/modules/activity/use/useDependenciesAndSuppliers"

export default {
  name: "ViewActivityApplication",
  props: {
    uid: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingDependencies: false,
      isLoadingSuppliers: false,
      isRefreshDependency: false,
      isRefreshSupplier: false,
    }
  },
  setup() {
    // PRE LOAD

    const { getDependencyList } = useDependencyList()
    const { getSuppliers } = useSupplierList()

    // Functions
    const { form, v$, updateDependenciesAndSuppliers } = useDependenciesAndSuppliers()
    const { getActivity } = useActivityDetail()

    const suppliers = ref([])
    const dependencies = ref([])
    const STEP_DEPENDENCIES = 4

    return {
      form,
      v$,
      suppliers,
      dependencies,
      getActivity,
      getDependencyList,
      getSuppliers,
      updateDependenciesAndSuppliers,
      STEP_DEPENDENCIES,
    }
  },

  mounted() {
    this.fetchActivity()
    this.fetchSuppliers()
    this.fetchDependencies()
  },

  methods: {
    /**
     * Save dependencies
     */
    async handleClickNext() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      this.isLoading = true

      this.form.step = this.STEP_DEPENDENCIES
      const response = await this.updateDependenciesAndSuppliers(this.form, this.uid)

      if (response && response.uid) {
        setTimeout(this.redirectToNextStep, 1000)
      }
      this.isLoading = false
    },

    /**
     * Redirect to next step
     */
    redirectToNextStep() {
      goto("ViewActivityTolerant")
    },

    // =========== Dependency_scenarios ================ //
    /**
     * open new tab to add dependency
     */
    handleAddMoreDependency() {
      this.isRefreshDependency = true
      const route = this.$router.resolve({ path: "/dependency-scenarios/new" })
      window.open(route.href)
    },

    // =========== Suppliers ================ //

    /**
     * open new tab to add supplier
     */
    handleAddMoreSupplier() {
      this.isRefreshSupplier = true
      const route = this.$router.resolve({ path: "/suppliers/new" })
      window.open(route.href)
    },

    /**
     * Back to pre Step
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

    /**
     * Fetch Activity
     */
    async fetchActivity() {
      this.isLoading = true

      const response = await this.getActivity(this.uid, ["suppliers", "dependencyScenarios"])

      if (response && response.uid) {
        this.transformFormData(response)
      }

      this.isLoading = false
    },

    /**
     * Transform data
     */
    transformFormData(response) {
      // supplier
      if (response.suppliers.length > 0) {
        this.form.suppliers = response.suppliers
      }

      // dependencies
      if (response.dependency_scenarios.length > 0) {
        this.form.dependency_scenarios = response.dependency_scenarios
      }
    },

    /**
     * Applications
     */
    async fetchSuppliers() {
      this.isRefreshSupplier = false
      this.isLoadingSuppliers = true
      const response = await this.getSuppliers()

      if (response && response.data) {
        this.suppliers = response.data
      }

      this.isLoadingSuppliers = false
    },

    /**
     * Equipments
     */
    async fetchDependencies() {
      this.isRefreshDependency = false
      this.isLoadingDependencies = true
      const response = await this.getDependencyList()

      if (response && response.data) {
        this.dependencies = response.data
      }

      this.isLoadingDependencies = false
    },
  },
  components: { ModalCancelAddActivity },
}
</script>
