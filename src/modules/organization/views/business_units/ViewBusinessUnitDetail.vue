<template>
  <RLayout :title="$t('organization.bu.edit')">
    <EcBox v-if="!isLoading" variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcBox>
        <!-- Name -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="businessUnit.name"
              componentName="EcInputText"
              :label="$t('organization.name')"
              :validator="v$"
              field="businessUnit.name"
              @input="v$.businessUnit.name.$touch()"
            />
          </EcBox>
        </EcFlex>

        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="businessUnit.is_active"
              componentName="EcToggle"
              :label="$t('organization.active')"
              showLabel
              :labelTrue="$t('organization.yes')"
              :labelFalse="$t('organization.no')"
            />
          </EcBox>
        </EcFlex>

        <!-- Divisions -->
        <EcFlex class="flex-wrap max-w-md items-center">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="businessUnit.division.uid"
              :label="$t('organization.bu.division')"
              componentName="EcSelect"
              :options="divisions"
              :valueKey="'uid'"
              :validator="v$"
              field="businessUnit.division.uid"
              @change="v$.businessUnit.division.uid.$touch()"
            />
          </EcBox>
          <EcSpinner v-if="isLoadingDivisions"></EcSpinner>
        </EcFlex>

        <!-- Desc -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="businessUnit.description"
              componentName="EcInputLongText"
              :rows="2"
              :label="$t('organization.bu.description')"
              :validator="v$"
              field="businessUnit.description"
              @input="v$.businessUnit.description.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>
    </EcBox>
    <RLoading v-else />

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isUpdateLoading" class="mt-6">
        <EcButton variant="primary" @click="handleClickUpdate">
          {{ $t("organization.update") }}
        </EcButton>
        <EcButton class="ml-4" variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("organization.bu.back") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
  </RLayout>
</template>

<script>
import { useBusinessUnitDetail } from "../../use/business_unit/useBusinessUnitDetail"
import { useDivisionList } from "../../use/division/useDivisionList"
import { goto } from "@/modules/core/composables"
import { ref } from "vue"
import RLoading from "@/modules/core/components/common/RLoading.vue"
import EcSpinner from "@/components/EcSpinner/index.vue"

export default {
  name: "ViewBusinessUnitDetail",
  data() {
    return {
      organizationUid: "",
      divisionUid: "",
      businessUnitUid: "",
      isLoading: false,
      isLoadingDivisions: false,
      isUpdateLoading: false,
    }
  },
  mounted() {
    const { divisionUid, businessUnitUid } = this.$route.params
    this.divisionUid = divisionUid
    this.businessUnitUid = businessUnitUid

    // Fetch Divisions
    this.fetchBusinessUnit()
    this.fetchDivisions()
  },
  setup() {
    const divisions = ref([])
    const { v$, businessUnit, getBusinessUnit, updateBusinessUnit } = useBusinessUnitDetail()
    const { getDivisions } = useDivisionList()
    return {
      v$,
      businessUnit,
      getBusinessUnit,
      updateBusinessUnit,
      getDivisions,
      divisions,
    }
  },
  computed: {},
  methods: {
    /**
     * Fetch division
     */
    async fetchDivisions() {
      this.isLoadingDivisions = true

      const response = await this.getDivisions()
      if (response && response.data) {
        this.divisions = response.data
      }

      this.isLoadingDivisions = false
    },

    /**
     * Fetch BU
     */
    async fetchBusinessUnit() {
      this.isLoading = true
      const response = await this.getBusinessUnit(this.divisionUid, this.businessUnitUid)
      if (response && response.uid) {
        this.businessUnit = response
      }
      this.isLoading = false
    },
    /**
     * Creaate orgranization
     */
    async handleClickUpdate() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }
      this.isUpdateLoading = true
      const response = await this.updateBusinessUnit(this.businessUnit, this.businessUnitUid)

      if (response && response.uid) {
        this.businessUnit = response
      }

      this.isUpdateLoading = false
    },
    /**
     * Back to department list
     */
    handleCreatedBusinessUnit() {
      goto("ViewDepartmentManagement")
    },
    /**
     * Back to department list
     */
    handleClickCancel() {
      goto("ViewDepartmentManagement")
    },
  },
  components: { RLoading, EcSpinner },
}
</script>
