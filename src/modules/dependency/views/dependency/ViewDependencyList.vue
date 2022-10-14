<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("dependency.dependencies") }}
        </EcHeadline>

        <!-- Add Dependenc -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddDependency">
          {{ $t("dependency.buttons.addDependency") }}
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

    <!-- Filter-->
    <EcBox class="xl:grid-cols-2 lg:grid-cols-1 grid sm:grid-cols-1 md:grid-cols-1 gap-2 mt-8 lg:mt-16">
      <EcBox class="grid grid-cols-5 lg:mt-2">
        <EcLabel class="text-start mt-2">{{ $t("dependency.filter") }}</EcLabel>
        <EcFlex class="col-span-4 grid grid-cols-2 gap-2">
          <!-- Resource type-->
          <RFormInput
            class="w-full"
            v-model="selectedCategory"
            componentName="EcSelect"
            :options="resourceCategories"
            :valueKey="'uid'"
            :allowSelectNothing="true"
            :placeholder="categoryPlaceHolder"
          />
        </EcFlex>
      </EcBox>
      <EcBox class="lg:flex-wrap grid sm:grid-cols-1 md:grid-cols-1s gap-2 justify-items-end">
        <!-- Export Resources -->

        <EcButton
          class="mb-3 lg:mb-0 w-1/3"
          :iconPrefix="exportResourcesIcon"
          variant="primary-sm"
          @click="handleClickDownloadDependencies"
        >
          {{ $t("dependency.buttons.exportDependencies") }}
        </EcButton>

        <!-- View Dependency Category -->
        <!-- <EcButton class="mb-3 lg:mb-0" iconPrefix="Resource" variant="primary-sm" @click="handleClickViewCategories">
          {{ $t("dependency.buttons.viewDependencyCategories") }}
        </EcButton> -->

        <!-- View Dependency Owner -->
        <!-- <EcButton class="mb-3 lg:mb-0" iconPrefix="UserGroup" variant="primary-sm" @click="handleClickViewOwners">
          {{ $t("dependency.buttons.viewDependencyOwners") }}
        </EcButton> -->
      </EcBox>
    </EcBox>

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="filteredResources" class="mt-4 lg:mt-6">
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
            <EcText class="w-24">
              {{ item.description }}
            </EcText>
          </RTableCell>

          <!-- Category -->
          <RTableCell>
            <EcText class="w-24">
              {{ item?.category?.name }}
            </EcText>
          </RTableCell>

          <!-- status -->
          <RTableCell>
            <EcText :variant="getResourceStatusType(item.status)" class="w-32">
              {{ getResourceStatus(item.status) }}
            </EcText>
          </RTableCell>

          <!-- created at -->
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
                  <EcText class="font-medium">{{ $t("dependency.buttons.view") }}</EcText>
                </EcFlex> -->

                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditDependency(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("dependency.buttons.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleOpenDeleteModal(item.uid, item.name)"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("dependency.buttons.delete") }}</EcText>
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
        :resourceUid="toDeleteResourceUid"
        :resourceName="toDeleteResourceName"
        :isModalDeleteResourceOpen="isModalDeleteOpen"
        @handleCloseDeleteModal="handleCloseDeleteModal"
        @handleDeleteCallback="handleDeleteCallback"
      />
    </teleport>
  </RLayout>
</template>

<script>
import { useResourceList } from "@/modules/dependency/use/dependency/useResourceList"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import { ref } from "vue"
import { useCategoryList } from "../../use/category/useCategoryList"
import { useResourceStatusEnum } from "../../use/dependency/useResourceStatusEnum"
import ModalDeleteResource from "../../components/ModalDeleteResource.vue"

export default {
  name: "ViewResourceList",
  setup() {
    // Pre load
    const { getResourceCategoryList } = useCategoryList()
    const globalStore = useGlobalStore()
    const { getResourceList, downloadResources, resources, totalItems, skip, limit, currentPage } = useResourceList()

    const { statuses } = useResourceStatusEnum()
    const resourceCategories = ref([])

    return {
      globalStore,
      getResourceList,
      downloadResources,
      getResourceCategoryList,
      resources,
      statuses,
      skip,
      limit,
      currentPage,
      totalItems,
      resourceCategories,
    }
  },
  data() {
    return {
      headerData: [
        { label: this.$t("dependency.labels.name") },
        { label: this.$t("dependency.labels.description") },
        { label: this.$t("dependency.labels.category") },
        { label: this.$t("dependency.labels.status") },
        { label: this.$t("dependency.labels.createdAt") },
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
    this.fetchResources()
    this.fetchResourceCategories()
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
      return this.isLoadingCategories ? this.$t("dependency.placeholders.loading") : this.$t("dependency.placeholders.category")
    },

    /**
     * Filtered
     */
    filteredResources() {
      if (this.selectedCategory.length > 0) {
        return this.resources.filter((resource) => {
          return resource.category.uid === this.selectedCategory
        })
      }

      return this.resources
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
    async fetchResources() {
      this.isLoading = true

      const resourceRes = await this.getResourceList()

      if (resourceRes && resourceRes.data) {
        this.resources = resourceRes.data
      }

      this.isLoading = false
    },
    /**
     * convert resource status to string status
     * @param value
     * @returns {string}
     */
    getResourceStatus(value) {
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
    getResourceStatusType(value) {
      switch (value) {
        case 1:
          return "pill-cSuccess-inv"

        case 2:
          return "pill-cWarning-inv"

        default:
          return "pill-cError-inv"
      }
    },

    // Handle events
    /**
     * Download
     */
    async handleClickDownloadDependencies() {
      this.isDownloading = true
      await this.downloadResources(this.selectedCategory)
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
    handleClickAddDependency() {
      goto("ViewDependencyNew")
    },
    /**
     *
     * @param {*} dependencyUid
     */
    handleClickEditDependency(dependencyUid) {
      goto("ViewResourceDetail", {
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
      this.fetchResources()
    },
    // ==== PRE-LOAD ==========
    /**
     * Fetch Categories
     */
    async fetchResourceCategories() {
      this.isLoadingCategories = true
      const response = await this.getResourceCategoryList()
      if (response) {
        this.resourceCategories = response
      }
      this.isLoadingCategories = false
    },
  },
  components: { ModalDeleteResource },
}
</script>
