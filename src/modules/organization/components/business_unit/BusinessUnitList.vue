<template>
  <EcBox class="overflow-x-auto lg:overflow-visible mt-8 lg:mt-8 p-2">
    <!-- Header -->
    <EcFlex v-if="!isLoading" class="items-center">
      <EcHeadline as="h4" variant="h4" class="text-c1-800"> {{ organization?.name }}'s business units </EcHeadline>

      <!-- Selected division -->
      <EcText
        v-if="selectedDivision"
        class="box-content text-xs text-c1-500 ml-4 p-1 border-1 border-c1-200 rounded-lg shadow-lg hover:cursor-pointer"
        @click="handleRemoveSelectedDivision"
      >
        {{ selectedDivision?.name }} <span class="font-semibold">x</span>
      </EcText>
    </EcFlex>

    <!-- Add button and Search box -->
    <EcFlex class="flex-grow items-center w-full md:w-auto mt-8 mb-4 mr-4">
      <EcFlex class="justify-start w-1/2">
        <!-- Add button -->
        <EcButton variant="primary-sm" class="mb-3 lg:mb-0" iconPrefix="PlusCircle" @click="handleClickAddBusinessUnit">
          {{ $t("organization.add") }}
        </EcButton>
      </EcFlex>

      <!-- Search box -->
      <EcFlex class="justify-end w-1/2">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('organization.searchBU')"
          class="w-full md:max-w-xs text-sm"
          @update:modelValue="onFilter"
          @clear-search="handleClearSearch"
        />
      </EcFlex>
    </EcFlex>

    <!-- Business Unit items -->
    <EcFlex v-if="!this.isLoading" class="lg:flex-wrap grid grid-cols-1">
      <BusinessUnitCardItem
        v-for="item in filteredBusinessUnit"
        :organizationUid="organization?.uid"
        :businessUnit="item"
        :key="item.uid"
        @handleDeletedBuItem="handleDeletedBuItem"
      />

      <!-- No data found -->
      <EcBox v-show="businessUnits.length <= 0">
        <EcText>{{ $t("core.noDataHere") }}</EcText>
      </EcBox>
    </EcFlex>

    <EcFlex v-else class="items-center">
      <EcSpinner />
    </EcFlex>
  </EcBox>
</template>

<script>
import BusinessUnitCardItem from "./BusinessUnitCardItem"
import { useBusinessUnitList } from "../../use/business_unit/useBusinessUnitList"
import { goto } from "@/modules/core/composables"
import { ref } from "vue"
import EcBox from "@/components/EcBox/index.vue"
import EcFlex from "@/components/EcFlex/index.vue"

export default {
  name: "BusinessUnitList",
  components: {
    BusinessUnitCardItem,
    EcBox,
    EcFlex,
  },
  data() {
    return {
      isLoading: true,
      searchQuery: "",
      onFilter: "",
    }
  },

  setup() {
    const businessUnits = ref([])

    const { adminGetBusinessUnitsByOrg } = useBusinessUnitList()
    return {
      adminGetBusinessUnitsByOrg,
      businessUnits,
    }
  },
  props: {
    organization: {
      type: Object,
    },
    selectedDivision: {
      type: Object,
    },
  },

  computed: {
    organizationOrDivisionName() {
      return this.selectedDivision ? this.selectedDivision?.name : this.organization?.name
    },

    filteredBusinessUnit() {
      if (this.selectedDivision) {
        return this.businessUnits.filter((bu) => {
          return bu.division?.uid === this.selectedDivision?.uid
        })
      }

      return this.businessUnits
    },
  },

  mounted() {
    this.fetchBusinessUnits()
  },
  methods: {
    async fetchBusinessUnits() {
      if (!this.organization.uid) {
        return
      }

      this.isLoading = true

      const response = await this.adminGetBusinessUnitsByOrg(this.organization.uid)

      this.isLoading = false

      if (response && response.data) {
        this.businessUnits = response.data
      }
    },
    handleClickAddBusinessUnit() {
      goto("ViewBusinessUnitNew")
    },

    async handleDeletedBuItem() {
      await this.fetchBusinessUnits()
    },

    handleClearSearch() {},

    /**
     * Remove selected division
     */
    handleRemoveSelectedDivision() {
      this.$emit("handleRemoveSelectedDivision")
    },
  },
  watch: {
    selectedDivision(div) {
      console.log("Changed to: ", div)
    },

    async organization(org) {
      await this.fetchBusinessUnits()
    },
  },
}
</script>
