<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("dependencyScenario.dependencies") }}
        </EcHeadline>
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

    <!-- Filter-->
    <EcBox class="xl:grid-cols-2 lg:grid-cols-1 grid sm:grid-cols-1 md:grid-cols-1 gap-2 mt-8 lg:mt-16">
      <EcBox class="grid grid-cols-5 lg:mt-2"></EcBox>
      <EcBox class="lg:flex-wrap grid xs:grid-cols-1 md:grid-cols-2 gap-2 justify-items-end">
        <!-- Add Dependency -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddDependency">
          {{ $t("dependencyScenario.buttons.addDependency") }}
        </EcButton>

        <!-- Export Dependency -->
        <EcButton
          class="mb-3 lg:mb-0"
          :iconPrefix="exportResourcesIcon"
          variant="primary-sm"
          @click="handleClickDownloadDependencyScenarios"
        >
          {{ $t("dependencyScenario.buttons.exportDependencies") }}
        </EcButton>
      </EcBox>
    </EcBox>

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="filteredDependencies" class="mt-4 lg:mt-6">
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

          <!-- Desc -->
          <RTableCell>
            <EcText class="w-32">
              {{ item.description }}
            </EcText>
          </RTableCell>

          <!-- status -->
          <RTableCell>
            <EcText :variant="getDependencyScenarioStatusType(item.status)" class="w-24">
              {{ getDependencyScenarioStatus(item.status) }}
            </EcText>
          </RTableCell>

          <!-- Targets -->
          <RTableCell>
            <EcText class="w-4"> {{ item.targetCount }} </EcText>
          </RTableCell>

          <!-- Upstream -->
          <RTableCell>
            <EcText class="w-4"> {{ item.upstreamCount }} </EcText>
          </RTableCell>

          <!-- Downstream -->
          <RTableCell>
            <EcText class="w-4"> {{ item.downstreamCount }} </EcText>
          </RTableCell>

          <!-- created at -->
          <RTableCell>
            <EcText class="pr-2">
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
                  <EcText class="font-medium">{{ $t("dependencyScenario.buttons.view") }}</EcText>
                </EcFlex> -->

                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditDependency(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("dependencyScenario.buttons.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleOpenDeleteModal(item.uid, item.name)"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("dependencyScenario.buttons.delete") }}</EcText>
                </EcFlex>
              </RTableAction>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>

    <!-- Pagination -->
    <EcFlex class="flex-col mt-8 sm:mt-12 sm:flex-row" variant="basic">
      <RPaginationStatus :currentPage="currentPage" :limit="limit" :totalCount="totalItems" class="mb-4 sm:mb-0" />
      <RPagination v-model="currentPage" :itemPerPage="limit" :totalItems="totalItems" @input="setPage($event)" />
    </EcFlex>

    <!-- Modal  delete resource -->
    <teleport to="#layer1">
      <ModalDeleteResource
        :dependencyScenarioUid="toDeleteResourceUid"
        :resourceName="toDeleteResourceName"
        :isModalDeleteResourceOpen="isModalDeleteOpen"
        @handleCloseDeleteModal="handleCloseDeleteModal"
        @handleDeleteCallback="handleDeleteCallback"
      />
    </teleport>
  </RLayout>
</template>

<script>
import { useDependencyList } from "@/modules/dependency/use/dependency/useDependencyList"
import { useDependencyFactor } from "@/modules/dependency/use/dependencyFactor/useDependencyFactor"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import { useDependencyScenarioStatusEnum } from "../../use/dependency/useDependencyScenarioStatusEnum"
import ModalDeleteResource from "../../components/ModalDeleteDependencyScenario.vue"

export default {
  name: "ViewDependencyList",
  setup() {
    // Pre load
    const globalStore = useGlobalStore()
    const { getDependencyList, downloadDependencies, dependencies, totalItems, skip, limit, currentPage } = useDependencyList()

    const { dependencyFactors, getDependencyFactors } = useDependencyFactor()

    const { statuses } = useDependencyScenarioStatusEnum()

    return {
      globalStore,
      getDependencyList,
      downloadDependencies,
      dependencies,
      dependencyFactors,
      getDependencyFactors,

      statuses,
      skip,
      limit,
      currentPage,
      totalItems,
    }
  },
  data() {
    return {
      headerData: [
        { label: this.$t("dependencyScenario.labels.name") },
        { label: this.$t("dependencyScenario.labels.description") },
        { label: this.$t("dependencyScenario.labels.status") },
        { label: this.$t("dependencyScenario.labels.targets") },
        { label: this.$t("dependencyScenario.labels.upstream") },
        { label: this.$t("dependencyScenario.labels.downstream") },
        { label: this.$t("dependencyScenario.labels.createdAt") },
      ],
      selectedCategory: "",
      searchQuery: "",
      onFilter: "",
      isLoading: false,
      isLoadingCategories: false,
      isDownloading: false,
      isModalDeleteOpen: false,
      // Resource uid to delete
      toDeleteResourceUid: null,
      toDeleteResourceName: "",
    }
  },
  mounted() {
    this.fetchDependencies()
    this.fetchDependencyFactors()
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
    exportResourcesIcon() {
      return this.isDownloading ? "Spinner" : "DocumentDownload"
    },

    categoryPlaceHolder() {
      return this.isLoadingCategories
        ? this.$t("dependencyScenario.placeholders.loading")
        : this.$t("dependencyScenario.placeholders.category")
    },

    /**
     * Filtered
     */
    filteredDependencies() {
      if (this.selectedCategory.length > 0) {
        return this.dependencies.filter((dependency) => {
          return dependency.category.uid === this.selectedCategory
        })
      }

      return this.dependencies
    },
  },
  watch: {
    currentPage() {},
  },
  methods: {
    formatData,

    /**
     * fetch activities
     * @returns {Promise<void>}
     */
    async fetchDependencies() {
      this.isLoading = true

      const dependenciesRes = await this.getDependencyList()

      if (dependenciesRes && dependenciesRes.data) {
        this.dependencies = dependenciesRes.data
      }

      this.isLoading = false
    },
    /**
     * convert resource status to string status
     * @param value
     * @returns {string}
     */
    getDependencyScenarioStatus(value) {
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
    getDependencyScenarioStatusType(value) {
      switch (value) {
        case 1:
          return "pill-cSuccess-inv"

        case 2:
          return "pill-cWarning-inv"

        case 3:
          return "pill-c2"

        default:
          return "pill-cError-inv"
      }
    },

    // Handle events
    /**
     * Download
     */
    async handleClickDownloadDependencyScenarios() {
      this.isDownloading = true
      await this.downloadDependencies()
      this.isDownloading = false
    },

    /**
     * Add new activity
     */
    handleClickAddDependency() {
      goto("ViewDependencyScenarioNew")
    },
    /**
     *
     * @param {*} dependencyUid
     */
    handleClickEditDependency(dependencyUid) {
      goto("ViewDependencyScenarioDetail", {
        params: {
          uid: dependencyUid,
        },
      })
    },
    handleClearSearch() {},
    /**
     * Open delete resource modal
     */
    handleOpenDeleteModal(resourceUid, resourceName) {
      this.toDeleteResourceUid = resourceUid
      this.toDeleteResourceName = resourceName
      this.isModalDeleteOpen = true
    },

    /**
     * Open delete resource modal
     */
    handleCloseDeleteModal() {
      this.toDeleteResourceUid = null
      this.toDeleteResourceName = ""
      this.isModalDeleteOpen = false
    },

    /**
     * Callback after delete
     */
    handleDeleteCallback() {
      this.fetchDependencies()
    },
    // ==== PRE-LOAD ==========
    /**
     * Fetch Dependency Factor
     */
    async fetchDependencyFactors() {
      this.isLoadingCategories = true
      const response = await this.getDependencyFactors()
      if (response) {
        this.dependencyFactors = response
      }
      this.isLoadingCategories = false
    },
  },
  components: { ModalDeleteResource },
}
</script>
