<template>
  <EcBox class="overflow-x-auto lg:overflow-visible mt-8 lg:mt-8 p-2">
    <!-- Header -->
    <EcFlex v-if="!isLoading" class="items-center">
      <EcHeadline as="h4" variant="h4" class="text-c1-800"> {{ organization.name }}'s divisions </EcHeadline>
    </EcFlex>

    <!-- Add button and Search box -->
    <EcFlex class="flex-grow items-center w-full md:w-auto mt-8 mb-4 mr-4">
      <EcFlex class="justify-start w-1/2">
        <!-- Add button -->
        <EcButton variant="primary-sm" class="mb-3 lg:mb-0" iconPrefix="PlusCircle" @click="handleClickAddDivision">
          {{ $t("organization.add") }}
        </EcButton>
      </EcFlex>

      <!-- Search box -->
      <EcFlex class="justify-end w-1/2">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('organization.searchDivision')"
          class="w-full md:max-w-xs text-sm"
          @update:modelValue="onFilter"
          @clear-search="handleClearSearch"
        />
      </EcFlex>
    </EcFlex>

    <!-- Divison list -->
    <EcFlex v-if="!isLoading" class="lg:flex-wrap grid grid-cols-1 md:grid-cols-2 gap-2">
      <DivisionListCardItem
        v-for="item in divisions"
        :organization="organization"
        :division="item"
        :key="item.uid"
        :isActive="selectedDivision?.uid === item.uid"
        @handleDivisionCardChange="handleDivisionCardChange"
      />

      <!-- No data found -->
      <EcBox v-show="divisions.length === 0">
        <EcText>{{ $t("core.noDataHere") }}</EcText>
      </EcBox>
    </EcFlex>

    <EcFlex v-else class="mt-4">
      <EcSpinner />
    </EcFlex>
  </EcBox>
</template>

<script>
import DivisionListCardItem from "./DivisionListCardItem"
import { useDivisionList } from "../../use/division/useDivisionList"
import EcFlex from "@/components/EcFlex/index.vue"
import EcSpinner from "@/components/EcSpinner/index.vue"
import { goto } from "@/modules/core/composables"
import { ref } from "vue"

export default {
  name: "DivisionList",
  components: {
    DivisionListCardItem,
    EcFlex,
    EcSpinner,
  },
  data() {
    return {
      isLoading: false,
      searchQuery: "",
      onFilter: "",
    }
  },

  setup() {
    const divisions = ref([])
    const { adminGetDivisions } = useDivisionList()

    return {
      adminGetDivisions,
      divisions,
    }
  },

  emits: ["handleDivisionCardChangeOnManagement"],

  props: {
    organization: {
      type: Object,
    },

    selectedDivision: {
      type: Object,
    },
  },

  computed: {},

  mounted() {
    this.fetchDivisions()
  },

  methods: {
    /**
     * Fetch Divisions
     */
    async fetchDivisions() {
      this.isLoading = true

      if (!this.organization.uid) {
        return
      }

      const response = await this.adminGetDivisions(this.organization.uid)

      if (response && response.data) {
        this.divisions = response.data
      }
      this.isLoading = false
    },

    /**
     * Click Add division
     */
    handleClickAddDivision() {
      goto("ViewDivisionNew")
    },

    handleDivisionCardChange(division) {
      this.$emit("handleDivisionCardChangeOnManagement", division)
    },

    handleClearSearch() {},
  },

  watch: {
    selectedDivision(division) {
      this.$emit("handleDivisionCardChangeOnManagement", division)
    },

    async organization(org) {
      await this.fetchDivisions()
    },
  },
}
</script>
