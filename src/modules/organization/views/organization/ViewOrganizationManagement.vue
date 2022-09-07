<template>
  <RLayout :title="organization.name">
    <RLayoutTwoCol :isLoading="isLoading" leftCls="lg:w-7/12 lg:pr-4 mb-8" rightCls="lg:w-5/12 lg:pr-4 mb-8">
      <template #left>
        <EcBox variant="card-1" class="width-full px-4 sm:px-10">
          <!-- Division list -->
          <DivisionList
            :organization="organization"
            :selectedDivision="selectedDivision"
            @handleDivisionCardChangeOnManagement="handleDivisionCardChangeOnManagement"
          />
        </EcBox>
      </template>
      <template #right>
        <!-- Bussiness Units -->
        <EcBox variant="card-1" class="width-full px-4 sm:px-10">
          <BusinessUnitList
            :organization="organization"
            :selectedDivision="selectedDivision"
            @handleRemoveSelectedDivision="handleRemoveSelectedDivision"
          />
        </EcBox>
      </template>
    </RLayoutTwoCol>
  </RLayout>
</template>

<script>
import { useOrganizationDetail } from "./../../use/organization/useOrganizationDetail"
import { ref } from "vue"
import DivisionList from "../../components/division/DivisionList.vue"
import BusinessUnitList from "../../components/business_unit/BusinessUnitList.vue"

export default {
  name: "ViewOrganizationManagement",
  data() {
    return {
      isLoading: false,
      logoTitle: "Logo",
      isModalDeleteOpen: false,
      confirmedOrganizationName: "",
    }
  },
  async beforeMount() {
    console.log(this.$route.params)
    await this.fetchOrganization()
  },

  mounted() {},

  setup() {
    const selectedDivision = ref()

    const organization = ref({})

    const { getOrganization } = useOrganizationDetail()

    return {
      getOrganization,
      organization,
      selectedDivision,
    }
  },
  computed: {
    matchedName() {
      return this.confirmedOrganizationName === this.organization.name
    },
    isDeleteLoading() {
      return false
    },
  },
  methods: {
    /**
     * Fetch organization
     */
    async fetchOrganization() {
      const { organization, organizationUid } = this.$route.params

      if (organization) {
        this.organization = organization
      } else {
        this.isLoading = true
        const response = await this.getOrganization(organizationUid)
        this.isLoading = false

        if (response && response.uid) {
          this.organization = response
        }
      }
    },

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
