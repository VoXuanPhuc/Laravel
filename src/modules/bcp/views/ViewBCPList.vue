<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("bcp.businessContinuity") }}
        </EcHeadline>
      </EcFlex>

      <!-- Search box -->
      <EcFlex class="flex-grow justify-end items-center w-full md:w-auto">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('organization.search')"
          class="w-full md:max-w-xs"
          @clear-search="handleClearSearch"
          @search="handleSearch"
        />
      </EcFlex>
    </EcFlex>

    <!-- Filter-->
    <EcBox class="items-center mt-6 grid grid-cols-2">
      <!-- Filter -->
      <EcFlex class="items-center">
        <!-- From -->
        <EcLabel class="text-start">{{ $t("bcp.labels.from") }}</EcLabel>
        <EcFlex class="ml-2">
          <RFormInput v-model="dateFilter.from" componentName="EcDatePicker" field="dateFilter.from" />
        </EcFlex>

        <!-- To -->
        <EcLabel class="items-center ml-4">{{ $t("bcp.labels.to") }}</EcLabel>
        <EcFlex class="ml-2">
          <RFormInput v-model="dateFilter.to" componentName="EcDatePicker" field="dateFilter.to" />
        </EcFlex>
      </EcFlex>

      <!-- Actions -->
      <EcFlex class="justify-end">
        <!-- Add BCP -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddBCP">
          {{ $t("bcp.buttons.addBCP") }}
        </EcButton>
        <!-- Export BCPs -->
        <EcButton
          class="col-end-2 ml-2 mb-3 lg:mb-0"
          :iconPrefix="exportBCPsIcon"
          variant="primary-sm"
          @click="handleClickDownloadBCPs"
        >
          {{ $t("bcp.buttons.exportBCPs") }}
        </EcButton>
      </EcFlex>
    </EcBox>

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="filteredBCPs" class="mt-4 lg:mt-6">
      <template #header>
        <RTableHeaderRow>
          <RTableHeaderCell v-for="(h, idx) in headerData" :key="idx" class="text-cBlack">
            {{ h.label }}
          </RTableHeaderCell>
          <RTableHeaderCell variant="gradient" />
        </RTableHeaderRow>
      </template>
      <template v-slot="{ item, last, first }">
        <RTableRow class="hover:bg-c0-100">
          <RTableCell>
            <EcText class="w-24">
              {{ item.name }}
            </EcText>
          </RTableCell>

          <!-- status -->
          <RTableCell>
            <EcText :variant="getBCPStatusType(item.status)" class="w-40">
              {{ getBCPStatus(item.status) }}
            </EcText>
          </RTableCell>

          <!-- Reports -->
          <RTableCell>
            <EcText class="pr-5">
              {{ item.report_count }}
            </EcText>
          </RTableCell>

          <!-- Due at -->
          <RTableCell>
            <EcText class="pr-5">
              {{ formatData(item.created_at, dateTimeFormat) }}
            </EcText>
          </RTableCell>

          <!-- Action -->
          <RTableCell :class="{ 'rounded-tr-lg': first, 'rounded-br-lg': last }" :isTruncate="false" variant="gradient">
            <EcFlex class="items-center justify-center h-full">
              <RTableAction class="w-30">
                <!-- View action -->
                <!-- <EcFlex class="items-center px-4 py-2 cursor-pointer text-cBlack hover:bg-c0-100">
                  <EcIcon class="mr-3" icon="Eye" />
                  <EcText class="font-medium">{{ $t("bcp.buttons.view") }}</EcText>
                </EcFlex> -->

                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditBCP(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("bcp.buttons.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleOpenDeleteModal(item.uid, item.name)"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("bcp.buttons.delete") }}</EcText>
                </EcFlex>
              </RTableAction>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>

    <!-- Pagination -->
    <EcFlex class="flex-col mt-8 sm:mt-12 sm:flex-row" variant="basic">
      <RPaginationStatus
        :currentPage="currentPage"
        :limit="filters.page.per_page"
        :totalCount="filters.page.total"
        class="mb-4 sm:mb-0"
      />
      <RPagination
        v-model="currentPage"
        :itemPerPage="filters.page.per_page"
        :totalItems="filters.page.total"
        @input="setPage($event)"
      />
    </EcFlex>

    <!-- Modal  delete BCP -->
    <teleport to="#layer1">
      <ModalDeleteBCP
        :bcpUid="toDeleteBCPUid"
        :bcpName="toDeleteBCPName"
        :isModalDeleteBCPOpen="isModalDeleteOpen"
        @handleCloseDeleteModal="handleCloseDeleteModal"
        @handleDeleteCallback="handleDeleteCallback"
      />
    </teleport>
  </RLayout>
</template>

<script>
import { useBCPList } from "@/modules/bcp/use/useBCPList"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import { ref } from "vue"
import { useBCPStatusEnum } from "../use/useBCPStatusEnum"
import ModalDeleteBCP from "../components/ModalDeleteBCP"

export default {
  name: "ViewBCPList",
  setup() {
    // Pre load
    const globalStore = useGlobalStore()
    const { getBCPList, downloadBCPs, bcps } = useBCPList()

    const { statuses } = useBCPStatusEnum()

    return {
      globalStore,
      getBCPList,
      downloadBCPs,
      bcps,
      statuses,
    }
  },
  data() {
    // Filters
    const filters = {
      filter: [],
      page: {
        number: 1,
        per_page: 1,
        total: 0,
      },
    }

    // Date filter
    const lastMonth = new Date()

    lastMonth.setMonth(lastMonth.getMonth() - 1)

    const dateFilter = ref({
      from: lastMonth.toISOString().slice(0, 10),
      to: new Date().toISOString().slice(0, 10),
    })

    return {
      headerData: [
        { label: this.$t("bcp.labels.name") },
        { label: this.$t("bcp.labels.status") },
        { label: this.$t("bcp.labels.report_num") },
        { label: this.$t("bcp.labels.createdAt") },
      ],

      searchQuery: "",
      currentPage: 1,
      isLoading: false,
      isLoadingCategories: false,
      isDownloading: false,
      isModalDeleteOpen: false,
      // BCP uid to delete
      toDeleteBCPUid: null,
      toDeleteBCPName: "",
      filters,
      dateFilter,
    }
  },
  mounted() {
    this.fetchBCPs()
  },
  computed: {
    /**
     * Format date
     */
    dateTimeFormat() {
      return this.globalStore.dateTimeFormat
    },

    /**
     * Export Icone
     */
    exportBCPsIcon() {
      return this.isDownloading ? "Spinner" : "DocumentDownload"
    },

    /**
     * Filtered
     */
    filteredBCPs() {
      return this.bcps
    },
  },
  watch: {
    currentPage() {
      this.filters.page.number = this.currentPage
      this.fetchBCPs()
    },

    dateFilter: {
      handler() {
        this.filters.page.number = this.currentPage
        this.fetchBCPs()
      },
      deep: true,
    },
  },
  methods: {
    formatData,

    /**
     * fetch activities
     * @returns {Promise<void>}
     */
    async fetchBCPs() {
      this.isLoading = true

      this.addDateToFilterQuery()

      const bcpsRes = await this.getBCPList(this.filters)

      if (bcpsRes && bcpsRes.data) {
        this.bcps = bcpsRes.data

        // Pagination
        this.currentPage = bcpsRes.current_page
        this.filters.page.per_page = bcpsRes.per_page
        this.filters.page.total = bcpsRes.total
      }

      this.isLoading = false
    },

    /**
     * Add date filter to filter querry
     */
    addDateToFilterQuery() {
      // Always add date filter before get list
      this.filters.filter[1] = {
        name: "created_at",
        type: "between",
        value: `${this.dateFilter.from} 00:00:00, ${this.dateFilter.to} 23:59:59`,
      }
    },
    /**
     * convert resource status to string status
     * @param value
     * @returns {string}
     */
    getBCPStatus(value) {
      const status = this.statuses.find((status) => {
        return status.value === value
      })

      return status?.name ?? "Unknown"
    },
    /**
     * get class property
     * @param value
     * @returns {string}
     */
    getBCPStatusType(value) {
      switch (value) {
        case 1:
          return "square-pill-td"

        case 2:
          return "square-pill-ip"

        case 3:
          return "square-pill-na"

        case 4:
          return "square-pill-ovd"

        case 5:
          return "square-pill-utd"

        default:
          return "square-pill-error"
      }
    },

    // Handle events
    /**
     * Download
     */
    async handleClickDownloadBCPs() {
      this.isDownloading = true
      await this.downloadBCPs(this.selectedCategory)
      this.isDownloading = false
    },

    /**
     * View category list
     */
    handleClickViewCategories() {
      goto("ViewCategoryList")
    },

    /**
     * View owner list
     */
    handleClickViewOwners() {
      goto("ViewOwnerList")
    },

    /**
     * Add new activity
     */
    handleClickAddBCP() {
      goto("ViewBCPNew")
    },
    /**
     *
     * @param {*} resourceUid
     */
    handleClickEditBCP(resourceUid) {
      goto("ViewBCPDetail", {
        params: {
          uid: resourceUid,
        },
      })
    },
    handleClearSearch() {
      this.filters.filter = []
      this.filters.page = {}

      this.fetchBCPs()
    },

    /**
     * Handle search
     */
    handleSearch() {
      this.filters.filter[0] = {
        name: "search",
        type: "contain",
        value: this.searchQuery,
      }

      // Always clear current paging for search performing
      this.filters.page = {}
      this.fetchBCPs()
    },
    /**
     * Open delete resource modal
     */
    handleOpenDeleteModal(bcpUid, bcpName) {
      this.toDeleteBCPUid = bcpUid
      this.toDeleteBCPName = bcpName
      this.isModalDeleteOpen = true
    },

    /**
     * Open delete resource modal
     */
    handleCloseDeleteModal() {
      this.toDeleteBCPUid = null
      this.toDeleteBCPName = ""
      this.isModalDeleteOpen = false
    },

    /**
     * Callback after delete
     */
    handleDeleteCallback() {
      this.fetchBCPs()
    },
    // ==== PRE-LOAD ==========
  },
  components: { ModalDeleteBCP },
}
</script>
