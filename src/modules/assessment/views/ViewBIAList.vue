<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("bia.assessments") }}
        </EcHeadline>
      </EcFlex>

      <!-- Search box -->
      <EcFlex class="flex-grow justify-end items-center w-full md:w-auto">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('bia.search')"
          class="w-full md:max-w-xs"
          @clear-search="handleClearSearch"
          @search="handleSearch"
        />
      </EcFlex>
    </EcFlex>

    <!-- Filter-->
    <EcBox class="items-center mt-6 grid grid-cols-1 md:grid-cols-2">
      <!-- Filter -->
      <EcFlex class="items-center">
        <!-- From -->
        <EcLabel class="text-start">{{ $t("bia.labels.from") }}</EcLabel>
        <EcFlex class="ml-2">
          <RFormInput v-model="dateFilter.from" componentName="EcDatePicker" field="dateFilter.from" />
        </EcFlex>

        <!-- To -->
        <EcLabel class="items-center ml-4">{{ $t("bia.labels.to") }}</EcLabel>
        <EcFlex class="ml-2">
          <RFormInput v-model="dateFilter.to" componentName="EcDatePicker" field="dateFilter.to" />
        </EcFlex>
      </EcFlex>

      <!-- Actions -->
      <EcFlex class="justify-center md:justify-end mt-6 md:mt-0">
        <!-- Add BIA -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddBIA">
          {{ $t("bia.buttons.addBIA") }}
        </EcButton>
        <!-- Export BIAs -->
        <EcButton
          class="col-end-2 ml-2 mb-3 lg:mb-0"
          :iconPrefix="exportBIAsIcon"
          variant="primary-sm"
          @click="handleClickDownloadBIAs"
        >
          {{ $t("bia.buttons.exportBIAs") }}
        </EcButton>
      </EcFlex>
    </EcBox>

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="filteredBIAs" class="mt-4 lg:mt-6">
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
            <EcText :variant="getBIAStatusType(item.status)" class="w-40">
              {{ getBIAStatus(item.status) }}
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
              <RTableAction class="w-30" :isLoading="recordLoading[item.uid]">
                <!-- View action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cBlack hover:bg-c0-100"
                  @click="handleClickExportBIA(item.uid)"
                >
                  <EcIcon class="mr-3" icon="DocumentDownload" />
                  <EcText class="font-medium">{{ $t("bia.buttons.export") }}</EcText>
                </EcFlex>

                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditBIA(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("bia.buttons.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleOpenDeleteModal(item.uid, item.name)"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("bia.buttons.delete") }}</EcText>
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

    <!-- Modal  delete BIA -->
    <teleport to="#layer1">
      <ModalDeleteBIA
        :biaUid="toDeleteBIAUid"
        :biaName="toDeleteBIAName"
        :isModalDeleteBIAOpen="isModalDeleteOpen"
        @handleCloseDeleteModal="handleCloseDeleteModal"
        @handleDeleteCallback="handleDeleteCallback"
      />
    </teleport>
  </RLayout>
</template>

<script>
import { useBIAList } from "@/modules/assessment/use/useBIAList"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import { ref } from "vue"
import { useBIAStatusEnum } from "../use/useBIAStatusEnum"
import ModalDeleteBIA from "../components/ModalDeleteAssessment.vue"

export default {
  name: "ViewBCPList",
  setup() {
    // Pre load
    const globalStore = useGlobalStore()
    const { getBIAList, downloadBIAs, exportBIARecord, bias } = useBIAList()

    const { statuses } = useBIAStatusEnum()

    return {
      globalStore,
      getBIAList,
      downloadBIAs,
      exportBIARecord,
      bias,
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

    // Recording loading
    const recordLoading = []

    return {
      headerData: [
        { label: this.$t("bia.labels.name") },
        { label: this.$t("bia.labels.status") },
        { label: this.$t("bia.labels.report_num") },
        { label: this.$t("bia.labels.createdAt") },
      ],

      searchQuery: "",
      currentPage: 1,
      isLoading: false,
      isLoadingCategories: false,
      isDownloading: false,
      isExporting: false,
      isModalDeleteOpen: false,
      // BIA uid to delete
      toDeleteBIAUid: null,
      toDeleteBIAName: "",
      filters,
      dateFilter,
      recordLoading,
    }
  },
  mounted() {
    this.fetchBIAs()
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
    exportBIAsIcon() {
      return this.isDownloading ? "Spinner" : "DocumentDownload"
    },

    /**
     * Filtered
     */
    filteredBIAs() {
      return this.bias
    },
  },
  watch: {
    currentPage() {
      this.filters.page.number = this.currentPage
      this.fetchBIAs()
    },

    dateFilter: {
      handler() {
        this.filters.page.number = this.currentPage
        this.fetchBIAs()
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
    async fetchBIAs() {
      this.isLoading = true

      this.addDateToFilterQuery()

      const bcpsRes = await this.getBIAList(this.filters)

      if (bcpsRes && bcpsRes.data) {
        this.bias = bcpsRes.data

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
    getBIAStatus(value) {
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
    getBIAStatusType(value) {
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

        case 6:
          return "square-pill-apv"

        default:
          return "square-pill-error"
      }
    },

    // Handle events
    /**
     * Download
     */
    async handleClickDownloadBIAs() {
      this.isDownloading = true
      await this.downloadBIAs()
      this.isDownloading = false
    },

    /**
     * Add new activity
     */
    handleClickAddBIA() {
      goto("ViewBIANew")
    },

    /**
     *
     * @param {*} uid
     */
    async handleClickExportBIA(uid) {
      this.recordLoading[uid] = true

      await this.exportBIARecord(uid)

      this.recordLoading[uid] = false
    },

    /**
     *
     * @param {*} uid
     */
    handleClickEditBIA(uid) {
      goto("ViewBIADetail", {
        params: {
          uid: uid,
        },
      })
    },
    handleClearSearch() {
      this.filters.filter = []
      this.filters.page = {}

      this.fetchBIAs()
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
      this.fetchBIAs()
    },
    /**
     * Open delete resource modal
     */
    handleOpenDeleteModal(biaUid, biaName) {
      this.toDeleteBIAUid = biaUid
      this.toDeleteBIAName = biaName
      this.isModalDeleteOpen = true
    },

    /**
     * Open delete resource modal
     */
    handleCloseDeleteModal() {
      this.toDeleteBIAUid = null
      this.toDeleteBIAName = ""
      this.isModalDeleteOpen = false
    },

    /**
     * Callback after delete
     */
    handleDeleteCallback() {
      this.fetchBIAs()
    },
    // ==== PRE-LOAD ==========
  },
  components: { ModalDeleteBIA },
}
</script>
