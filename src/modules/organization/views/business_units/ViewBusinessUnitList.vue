<template>
  <RLayout>
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("organization.bu.list") }}
        </EcHeadline>
        <EcButton variant="primary-sm" class="mb-3 lg:mb-0" iconPrefix="PlusCircle" @click="handleClickAddBusinessUnit">
          {{ $t("organization.bu.add") }}
        </EcButton>
      </EcFlex>
      <!-- Search box -->
      <EcFlex class="flex-grow justify-end items-center w-full md:w-auto">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('organization.search')"
          class="w-full md:max-w-xs"
          @update:modelValue="onFilter"
          @clear-search="handleClearSearch"
        />
      </EcFlex>
    </EcFlex>

    <EcBox v-if="!isLoading" variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <!-- Business Unit items -->
      <EcFlex v-if="!this.isLoading" class="w-6/12 lg:flex-wrap grid grid-cols-1">
        <BusinessUnitCardItem
          v-for="item in businessUnits"
          :organizationUid="organizationUid"
          :businessUnit="item"
          :key="item.uid"
          @handleDeletedBuItem="handleDeletedBuItem"
        />

        <!-- No data found -->
        <EcBox v-show="businessUnits?.length <= 0">
          <EcText>{{ $t("core.noDataHere") }}</EcText>
        </EcBox>
      </EcFlex>
    </EcBox>
    <RLoading v-else />

    <!-- Actions -->
    <EcBox class="width-full mt-8">
      <!-- Button create -->
      <EcFlex class="mt-6">
        <EcButton class="" variant="primary" @click="handleClickBack">
          {{ $t("organization.bu.back") }}
        </EcButton>
      </EcFlex>
    </EcBox>
  </RLayout>
</template>

<script>
import { useBusinessUnitList } from "../../use/business_unit/useBusinessUnitList"
import { useDivisionDetail } from "../../use/division/useDivisionDetail"
import { ref } from "vue"
import BusinessUnitCardItem from "../../components/business_unit/BusinessUnitCardItem.vue"
import { goto } from "@/modules/core/composables"

export default {
  name: "ViewOrganizationNew",
  data() {
    return {
      searchQuery: "",
      onFilter: "",
      organizationUid: "",
      divisionUid: "",
      isLoading: false,
      isLoadingDivisions: false,
      isUpdateLoading: false,
    }
  },
  mounted() {
    const { organizationUid, divisionUid } = this.$route.params

    this.organizationUid = organizationUid
    this.divisionUid = divisionUid

    // Fetch Divisions
    // this.fetchDivision()
    this.fetchBusinessUnits()
  },
  setup() {
    const { getDivision } = useDivisionDetail()
    const { adminGetBusinessUnitsByDivision } = useBusinessUnitList()
    const division = ref({})
    const businessUnits = ref([])
    return {
      division,
      businessUnits,
      getDivision,
      adminGetBusinessUnitsByDivision,
    }
  },
  computed: {},
  methods: {
    /**
     * Fetch division
     */
    async fetchDivision() {
      this.isLoading = true
      const response = await this.getDivision(this.organizationUid, this.divisionUid)
      if (response && response.uid) {
        this.division = response
      }
      this.isLoading = false
    },
    /**
     * Fetch business units
     */
    async fetchBusinessUnits() {
      this.isLoading = true
      const response = await this.adminGetBusinessUnitsByDivision(this.organizationUid, this.divisionUid)
      if (response && response.data) {
        this.businessUnits = response.data
      }
      this.isLoading = false
    },

    /**
     * Handle deleted BU item
     */
    async handleDeletedBuItem() {
      await this.fetchBusinessUnits()
    },

    /**
     *
     */
    handleClickAddBusinessUnit() {
      goto("ViewDivisionBusinessUnitNew", {
        params: {
          organizationUid: this.organizationUid,
          divisionUid: this.divisionUid,
        },
      })
    },

    /**
     * Clear search
     */
    handleClearSearch() {},

    /**
     * Back to division business unit list
     */
    handleClickBack() {
      goto("ViewOrganizationManagement", {
        params: {
          organizationUid: this.organizationUid,
          divisionUid: this.divisionUid,
        },
      })
    },
  },
  components: { BusinessUnitCardItem },
}
</script>
