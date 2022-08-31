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
    <OrganizationList :listData="organizationList" />

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
import { useOrganizationList } from "./../use/useOrganizationList"
import OrganizationList from "../components/OrganizationList.vue"

export default {
  name: "ViewOrganizationListing",
  setup() {
    const { state, send, searchQuery, organizationHeader, organizationList, totalItems, skip, limit, currentPage } =
      useOrganizationList()
    return {
      state,
      send,
      searchQuery,
      organizationHeader,
      organizationList,
      totalItems,
      skip,
      limit,
      currentPage,
    }
  },
  computed: {
    // ...mapState({
    //   dateTimeFormat: (state) => state.dateTimeFormat,
    // }),
    isLoading() {
      return false // return this.state.matches("reading.fetching")
    },
  },
  watch: {
    currentPage() {
      this.send("FILTER")
    },
  },
  methods: {
    formatData,
    onFilter: debounce(function () {
      this.currentPage = 1
      this.send("FILTER")
    }, 400),
    handleClearSearch() {
      this.searchQuery = ""
      this.send("FILTER")
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
    getEmail(contacts) {
      if (contacts) {
        const email = contacts.find((x) => x.type === "email")
        return email?.value
      }
      return "-"
    },
  },
  components: { OrganizationList },
}
</script>
