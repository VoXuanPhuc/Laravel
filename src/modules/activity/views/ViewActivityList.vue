<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("activity.activities") }}
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
      <EcBox class="grid grid-cols-5 lg:mt-2">
        <EcLabel class="text-start mt-2">{{ $t("activity.context") }}</EcLabel>
        <EcFlex class="col-span-4 grid grid-cols-2 gap-2">
          <!-- Division-->
          <RFormInput
            class="w-full"
            v-model="selectedDivision"
            componentName="EcSelect"
            :options="divisions"
            :valueKey="'uid'"
            :allowSelectNothing="true"
            :placeholder="loadingDivision"
          />

          <!-- Business-unit-->
          <RFormInput
            class="w-full"
            v-model="selectedBU"
            componentName="EcSelect"
            :options="businessUnits"
            :valueKey="'uid'"
            :allowSelectNothing="true"
            :placeholder="loadingBu"
          />
        </EcFlex>
      </EcBox>
      <EcBox class="lg:flex-wrap grid sm:grid-cols-1 md:grid-cols-3 gap-2 w-full justify-items-end">
        <EcBox></EcBox>
        <EcButton
          class="mb-3 lg:mb-0"
          :iconPrefix="exportActivityIcon"
          variant="primary-sm"
          @click="handleClickDownloadActivities"
        >
          {{ $t("activity.buttons.exportActivities") }}
        </EcButton>

        <!-- Add activity -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddActivity">
          {{ $t("activity.buttons.addActivity") }}
        </EcButton>
      </EcBox>
    </EcBox>

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="activities" class="mt-2 lg:mt-4">
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
          <!-- BU Name -->
          <!-- <RTableCell :class="{ 'rounded-tl-lg': first, 'rounded-bl-lg': last }">
            {{ item.business_unit?.name }}
          </RTableCell> -->
          <RTableCell>
            <EcText class="w-24">
              {{ item.name }}
            </EcText>
          </RTableCell>

          <!-- Step -->
          <RTableCell>
            <EcText :variant="getActivityStepType(item.step)" class="w-48">
              {{ getActivityStep(item.step) }}
            </EcText>
          </RTableCell>

          <!-- status -->
          <RTableCell>
            <EcText :variant="getActivityStatusType(item.status)" class="w-32">
              {{ getActivityStatus(item.status) }}
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
                <EcFlex class="items-center px-4 py-2 cursor-pointer text-cBlack hover:bg-c0-100">
                  <EcIcon class="mr-3" icon="Eye" />
                  <EcText class="font-medium">{{ $t("activity.buttons.view") }}</EcText>
                </EcFlex>

                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditActivity(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("activity.buttons.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  @click="handleOpenDeleteModal(item.uid, item.name)"
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("activity.buttons.delete") }}</EcText>
                </EcFlex>
              </RTableAction>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>
    <EcFlex class="flex-col mt-8 sm:mt-12 sm:flex-row" variant="basic">
      <RPaginationStatus :currentPage="currentPage" :limit="limit" :totalCount="totalItems" class="mb-4 sm:mb-0" />
      <RPagination v-model="currentPage" :itemPerPage="limit" :totalItems="totalItems" @input="setPage($event)" />
    </EcFlex>

    <!-- Modals -->
    <teleport to="#layer1">
      <!-- Modal Delete -->
      <EcModalSimple :isVisible="isModalDeleteOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
        <EcBox class="text-center">
          <EcFlex class="justify-center">
            <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
          </EcFlex>

          <!-- Messages -->
          <EcBox>
            <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
              {{ $t("activity.confirmToDelete") }}
            </EcHeadline>
            <!-- Org name -->
            <EcText class="text-lg font-semibold">
              {{ this.activityNameDelete }}
            </EcText>
            <EcText class="text-c0-500 mt-4">
              {{ $t("activity.confirmDeleteQuestion") }}
            </EcText>
            <EcText class="text-c0-500 mt-2">
              {{ $t("activity.confirmDeleteAction") }}
            </EcText>
          </EcBox>

          <!-- Confirm Org name -->
          <EcBox class="mt-4">
            <RFormInput componentName="EcInputText" v-model="confirmedActivityNameDelete"></RFormInput>
          </EcBox>

          <!-- Actions -->
          <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
            <EcButton v-if="isMatchedDeleteActivityName" variant="warning" @click="handleDeleteActivity">
              {{ $t("activity.buttons.delete") }}
            </EcButton>
            <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
              {{ $t("activity.buttons.cancel") }}
            </EcButton>
          </EcFlex>
          <EcFlex v-else class="items-center justify-center mt-10 h-10">
            <EcSpinner type="dots" />
          </EcFlex>
        </EcBox>
      </EcModalSimple>
    </teleport>
  </RLayout>
</template>

<script>
import { useActivityList } from "@/modules/activity/use/useActivityList"
import { useActivityDetail } from "@/modules/activity/use/useActivityDetail"
import { useDivisionList } from "@/modules/activity/use/useDivisionList"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import { useBusinessUnitList } from "@/modules/organization/use/business_unit/useBusinessUnitList"
import { ref } from "vue"
import EcBox from "@/components/EcBox/index.vue"

export default {
  name: "ViewActivityList",
  setup() {
    const globalStore = useGlobalStore()
    const {
      getActivityList,
      downloadActivities,
      fetchActivityListByDivisionUid,
      softDeleteActivity,
      activities,
      t,
      totalItems,
      skip,
      limit,
      currentPage,
    } = useActivityList()

    const { statuses } = useActivityDetail()

    const divisions = ref([])
    const businessUnits = ref([])
    const { getDivisionList } = useDivisionList()
    const { getBusinessUnits } = useBusinessUnitList()

    return {
      globalStore,
      getActivityList,
      downloadActivities,
      softDeleteActivity,
      activities,
      t,
      skip,
      limit,
      currentPage,
      totalItems,
      getDivisionList,
      getBusinessUnits,
      divisions,
      businessUnits,
      fetchActivityListByDivisionUid,
      statuses,
    }
  },
  data() {
    return {
      headerData: [
        // { label: this.$t("activity.label.businessUnit") },
        { label: this.$t("activity.labels.activityName") },
        { label: this.$t("activity.labels.step") },
        { label: this.$t("activity.labels.status") },
        { label: this.$t("activity.labels.createdAt") },
      ],
      selectedDivision: null,
      selectedBU: null,
      isLoading: false,
      isDivisionLoading: false,
      isBusinessUnitLoading: false,
      isDownloading: false,

      // Search
      searchQuery: "",
      onFilter: "",
      isModalDeleteOpen: false,
      activityNameDelete: "",
      activityUidDelete: "",
      isDeleteLoading: false,
      confirmedActivityNameDelete: "",
    }
  },
  mounted() {
    this.fetchActivities()
    this.fetchDivisions()
    this.fetchBusinessUnits()
  },
  computed: {
    dateTimeFormat() {
      return this.globalStore.dateTimeFormat
    },
    exportActivityIcon() {
      return this.isDownloading ? "Spinner" : "DocumentDownload"
    },

    loadingDivision() {
      return this.isDivisionLoading ? this.$t("activity.placeholders.loading") : this.$t("activity.placeholders.division")
    },

    loadingBu() {
      return this.isBusinessUnitLoading ? this.$t("activity.placeholders.loading") : this.$t("activity.placeholders.bu")
    },

    isMatchedDeleteActivityName() {
      return this.confirmedActivityNameDelete === this.activityNameDelete
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
    async fetchActivities() {
      this.isLoading = true
      const activityRes = await this.getActivityList()
      if (activityRes && activityRes.data) {
        this.activities = activityRes.data
      }
      this.isLoading = false
    },
    /**
     * convert activity status to string status
     * @param value
     * @returns {string}
     */
    getActivityStatus(value) {
      const status = this.statuses.find((item) => {
        return item.value === value
      })

      return status?.name || "N/A"
    },
    /**
     * get class property
     * @param value
     * @returns {string}
     */
    getActivityStatusType(value) {
      switch (value) {
        case 2:
          return "pill-c1"
        case 3:
          return "pill-cSuccess-inv"
        case 4:
          return "pill-cWarning-inv"
        default:
          return "pill-disabled"
      }
    },

    /**
     *
     * @param {*} value
     */
    getActivityStep(value) {
      return value === 1 ? "Basic info" : value === 2 ? "Remote access" : "Equipments"
    },

    /**
     *
     * @param {*} value
     */
    getActivityStepType(value) {
      return value === 1 ? "pill-disabled" : value === 2 ? "pill-c1" : "pill-cSuccess-inv"
    },
    // Handle events
    /**
     * Download
     */
    async handleClickDownloadActivities() {
      this.isDownloading = true
      await this.downloadActivities(this.selectedDivision, this.selectedBU)
      this.isDownloading = false
    },
    /**
     * Add new activity
     */
    handleClickAddActivity() {
      goto("ViewActivityNew")
    },
    /**
     *
     * @param {*} actitivtyUid
     */
    handleClickEditActivity(actitivtyUid) {
      goto("ViewActivityDetail", {
        params: {
          uid: actitivtyUid,
        },
      })
    },

    // Search
    handleClearSearch() {},
    // ==== PRE-LOAD ==========
    /**
     * Fetch Division
     */
    async fetchDivisions() {
      this.isDivisionLoading = true
      const divisionRes = await this.getDivisionList()
      if (divisionRes && divisionRes.data) {
        this.divisions = divisionRes.data
      }
      this.isDivisionLoading = false
    },
    /**
     * Fetch BU
     */
    async fetchBusinessUnits() {
      this.isBusinessUnitLoading = true

      const buRes = await this.getBusinessUnits()
      if (buRes && buRes.data) {
        this.businessUnits = buRes.data
      }

      this.isBusinessUnitLoading = false
    },

    // =========== Delete =========== \\

    /**
     * handle delete activity
     * @param uid
     */
    async handleDeleteActivity() {
      this.isDeleteLoading = true
      await this.softDeleteActivity(this.activityUidDelete)
      await this.fetchActivities()
      this.handleCloseDeleteModal()
      this.isDeleteLoading = false
    },

    /** Open delete modal */
    handleOpenDeleteModal(activityUid, activityName) {
      this.activityNameDelete = activityName
      this.activityUidDelete = activityUid
      this.isModalDeleteOpen = true
    },

    /** Close delete modal */
    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
      this.confirmedActivityNameDelete = ""
      this.activityNameDelete = ""
      this.activityUidDelete = ""
    },
  },
  components: { EcBox },
}
</script>
