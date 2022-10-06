<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("organization.organizations") }}
        </EcHeadline>
        <EcButton variant="primary-sm" class="mb-3 lg:mb-0" iconPrefix="PlusCircle" @click="handleClickAddOrganization">
          {{ $t("organization.add") }}
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

    <!-- Organization List -->
    <OrganizationList v-if="!isLoading" :listData="organizationList" />
    <RLoading v-else />

    <!-- Pagination -->
    <EcFlex class="mt-8 flex-col sm:mt-12 sm:flex-row" variant="basic">
      <RPaginationStatus class="mb-4 sm:mb-0" :currentPage="currentPage" :totalCount="totalItems" :limit="limit" />
      <RPagination v-model="currentPage" :totalItems="totalItems" :itemPerPage="limit" />
    </EcFlex>
  </RLayout>
</template>

<script>
import debounce from "lodash/debounce"
import { formatData } from "@/modules/core/composables"
import { useOrganizationList } from "./../../use/organization/useOrganizationList"
import OrganizationList from "../../components/organization/OrganizationList.vue"
import { ref } from "vue"
import RLoading from "@/modules/core/components/common/RLoading.vue"

export default {
  name: "ViewOrganizationList",
  data() {
    return {
      isLoading: false,
    }
  },

  setup() {
    const { searchQuery, getOrganizationList, totalItems, skip, limit, currentPage } = useOrganizationList()

    const organizationList = ref([])

    return {
      searchQuery,
      getOrganizationList,
      organizationList,
      totalItems,
      skip,
      limit,
      currentPage,
    }
  },

  watch: {
    currentPage() {},
  },

  mounted() {
    this.fetchOrganizations()
  },

  methods: {
    formatData,
    onFilter: debounce(function () {
      this.currentPage = 1
    }, 400),
    handleClearSearch() {
      this.searchQuery = ""
    },
    handleClickAddOrganization() {
      // Go to New Participant Page
      this.$router.push({
        name: "ViewOrganizationNew",
      })
    },
    handleClickOrganization(item) {
      // Go to Participant Detail Page
      this.$router.push({
        name: "ViewOrganizationDetail",
        params: {
          organizationId: item.id,
        },
      })
    },

    async fetchOrganizations() {
      this.isLoading = true
      const response = await this.getOrganizationList()
      this.organizationList = response.data

      this.isLoading = false
    },
  },
  components: { OrganizationList, RLoading },
}
</script>
