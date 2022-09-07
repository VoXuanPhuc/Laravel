<template>
  <RLayout :title="$t('organization.bu.new')">
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcBox>
        <!-- Name -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.name"
              componentName="EcInputText"
              :label="$t('organization.name')"
              :validator="v$"
              field="form.name"
              @input="v$.form.name.$touch()"
            />
          </EcBox>
        </EcFlex>

        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.is_active"
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
              v-model="form.division.uid"
              :label="$t('organization.bu.division')"
              componentName="EcSelect"
              :options="divisions"
              :valueKey="'uid'"
              :validator="v$"
              field="form.division.uid"
              disabled
              @change="v$.form.division.uid.$touch()"
            />
          </EcBox>
          <EcSpinner v-if="isLoadingDivisions"></EcSpinner>
        </EcFlex>

        <!-- Desc -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.description"
              componentName="EcInputLongText"
              :rows="2"
              :label="$t('organization.bu.description')"
              :validator="v$"
              field="form.description"
              @input="v$.form.description.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="primary" @click="handleClickCreate">
          {{ $t("organization.create") }}
        </EcButton>
        <EcButton class="ml-4" variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("organization.cancel") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
  </RLayout>
</template>

<script>
import { useBusinessUnitNew } from "../../use/business_unit/useBusinessUnitNew"
import { useDivisionList } from "../../use/division/useDivisionList"
import { goto } from "@/modules/core/composables"
import { ref } from "vue"
import EcSpinner from "@/components/EcSpinner/index.vue"

export default {
  name: "ViewOrganizationNew",
  data() {
    return {
      organizationUid: "",
      divisionUid: "",
      isLoading: false,
      isLoadingDivisions: false,
    }
  },
  mounted() {
    const { organizationUid, divisionUid } = this.$route.params
    this.organizationUid = organizationUid
    this.divisionUid = divisionUid
    this.form.division.uid = divisionUid
    // Fetch Divisions
    this.fetchDivisions()
  },
  setup() {
    const divisions = ref([])
    const { v$, form, createBusinessUnit } = useBusinessUnitNew()
    const { adminGetDivisions } = useDivisionList()
    return {
      v$,
      form,
      createBusinessUnit,
      adminGetDivisions,
      divisions,
    }
  },
  computed: {},
  methods: {
    async fetchDivisions() {
      this.isLoadingDivisions = true
      const response = await this.adminGetDivisions(this.organizationUid)
      if (response && response.data) {
        this.divisions = response.data
      }

      this.isLoadingDivisions = false
    },
    /**
     * Creaate orgranization
     */
    async handleClickCreate() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }
      this.isLoading = true
      const response = await this.createBusinessUnit(this.form, this.organizationUid, this.form.division.uid)
      this.isLoading = false
      if (response && response.uid) {
        this.form = response
        setTimeout(this.handleCreatedBusinessUnit, 300)
      }
    },
    /**
     * Back to organization list
     */
    handleCreatedBusinessUnit() {
      this.backToBusinessUnitList()
    },
    /**
     * Back to organization list
     */
    handleClickCancel() {
      this.backToBusinessUnitList()
    },

    backToBusinessUnitList() {
      goto("ViewBusinessUnitList", {
        params: {
          organizationUid: this.organizationUid,
          divisionUid: this.divisionUid,
        },
      })
    },
  },
  components: { EcSpinner },
}
</script>
