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
    <RTable :list="organizationList" :isLoading="isLoading" class="mt-6 lg:mt-10">
      <!-- Table Header -->
      <template #header>
        <RTableHeaderRow>
          <RTableHeaderCell v-for="header in organizationHeader" :key="header.name">
            {{ header.label }}
          </RTableHeaderCell>
          <RTableHeaderCell variant="gradient" />
        </RTableHeaderRow>
      </template>
      <!-- Table Body -->
      <template v-slot="{ item, last, first }">
        <RTableRow>
          <RTableCell :class="{ 'rounded-tl-lg': first, 'rounded-bl-lg': last }" class="text-c1-500 font-medium">
            <EcText class="inline-block cursor-pointer hover:underline select-none" @click="handleClickOrganization(item)">
              {{ formatData(item.name) }}
            </EcText>
          </RTableCell>
          <RTableCell>
            {{ formatData(item.internalCode) }}
          </RTableCell>
          <RTableCell :class="item.isActive ? 'text-cSuccess-500' : 'text-cError-500'">
            {{ item.isActive ? "Yes" : "No" }}
          </RTableCell>
          <RTableCell class="pr-20">
            {{ formatData(item.createdAt, dateTimeFormat) }}
          </RTableCell>
          <RTableCell variant="gradient" :class="{ 'rounded-tr-lg': first, 'rounded-br-lg': last }">
            <EcFlex class="justify-center items-center h-full">
              <EcButton variant="transparent-rounded" @click="handleClickOrganization(item)">
                <EcIcon width="20" height="20" class="text-c0-300" icon="Eye" />
              </EcButton>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>
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
// import { mapState } from "vuex"

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
        name: "ViewOrganizationCreate",
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
}
</script>
