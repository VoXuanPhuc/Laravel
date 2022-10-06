<template>
  <RLayout :title="tenantName">
    <RLayoutTwoCol :isLoading="isLoading" leftCls="lg:w-7/12 lg:pr-4 mb-8" rightCls="lg:w-5/12 lg:pr-4 mb-8">
      <template #left>
        <EcBox variant="card-1" class="width-full px-4 sm:px-10">
          <!-- Division list -->
          <DivisionList
            :selectedDivision="selectedDivision"
            @handleDivisionCardChangeOnManagement="handleDivisionCardChangeOnManagement"
          />
        </EcBox>
      </template>
      <template #right>
        <!-- Bussiness Units -->
        <EcBox variant="card-1" class="width-full px-4 sm:px-10">
          <BusinessUnitList :selectedDivision="selectedDivision" @handleRemoveSelectedDivision="handleRemoveSelectedDivision" />
        </EcBox>
      </template>
    </RLayoutTwoCol>
  </RLayout>
</template>

<script>
import { ref } from "vue"
import DivisionList from "../../components/division/DivisionList.vue"
import BusinessUnitList from "../../components/business_unit/BusinessUnitList.vue"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewDepartmentManagement",

  data() {
    return {
      isLoading: false,
      logoTitle: "Logo",
      isModalDeleteOpen: false,
      confirmedOrganizationName: "",
    }
  },

  setup() {
    const globalStore = useGlobalStore()
    const selectedDivision = ref()
    const organization = ref({})
    return {
      globalStore,
      selectedDivision,
      organization,
    }
  },
  computed: {
    tenantName() {
      return window.$cookies.get("tenantName") || this.globalStore.getTenantName
    },
    matchedName() {
      return this.confirmedOrganizationName === this.organization.name
    },
    isDeleteLoading() {
      return false
    },
  },
  methods: {
    handleDivisionCardChangeOnManagement(division) {
      this.selectedDivision = division
    },

    handleRemoveSelectedDivision() {
      this.selectedDivision = null
    },
  },
  components: { DivisionList, BusinessUnitList },
}
</script>
