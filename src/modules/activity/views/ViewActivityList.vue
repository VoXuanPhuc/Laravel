<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("activity.activities") }}
        </EcHeadline>
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
            :placeholder="$t('activity.placeholders.division')"
          />

          <!-- Business-unit-->
          <RFormInput
            class="w-full"
            v-model="selectedBU"
            componentName="EcSelect"
            :options="businessUnits"
            :valueKey="'uid'"
            :allowSelectNothing="true"
            :placeholder="$t('activity.placeholders.bu')"
          />
        </EcFlex>
      </EcBox>
      <EcBox class="lg:flex-wrap grid sm:grid-cols-1 md:grid-cols-3 gap-2">
        <EcButton
          class="mb-3 lg:mb-0"
          :iconPrefix="exportAcctivityIcon"
          variant="primary-sm"
          @click="handleClickDownloadActivities"
        >
          {{ $t("activity.button.exportActivities") }}
        </EcButton>
        <EcButton class="mb-3 lg:mb-0" iconPrefix="ChartSquareBar" variant="primary-sm">
          {{ $t("activity.button.timeImpactAnalysis") }}
        </EcButton>

        <!-- Add activity -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddActivity">
          {{ $t("activity.button.addActivity") }}
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
            <EcText :variant="getActivityConfirmationStepType(item.step)" class="w-48">
              {{ getActivityStep(item.step) }}
            </EcText>
          </RTableCell>

          <!-- status -->
          <RTableCell>
            <EcText :variant="getActivityConfirmationStatusType(item.status)" class="w-32">
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
                  <EcText class="font-medium">{{ $t("activity.button.view") }}</EcText>
                </EcFlex>

                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditActivity(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("activity.button.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100">
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("activity.button.delete") }}</EcText>
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
  </RLayout>
</template>

<script>
import { useActivityList } from "@/modules/activity/use/useActivityList"
import { useDivisionList } from "@/modules/activity/use/useDivisionList"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import { useBusinessUnitList } from "@/modules/organization/use/business_unit/useBusinessUnitList"
import { ref } from "vue"

export default {
  name: "ViewActivityList",
  setup() {
    const globalStore = useGlobalStore()
    const {
      getActivityList,
      downloadActivities,
      fetchActivityListByDivisionUid,
      activities,
      t,
      totalItems,
      skip,
      limit,
      currentPage,
    } = useActivityList()

    const divisions = ref([])
    const businessUnits = ref([])

    const { fetchDivisionList } = useDivisionList()
    const { tenantBusinessUnits } = useBusinessUnitList()

    return {
      globalStore,
      getActivityList,
      downloadActivities,
      activities,
      t,
      skip,
      limit,
      currentPage,
      totalItems,
      fetchDivisionList,
      tenantBusinessUnits,
      divisions,
      businessUnits,
      fetchActivityListByDivisionUid,
    }
  },

  data() {
    return {
      headerData: [
        // { label: this.$t("activity.label.businessUnit") },
        { label: this.$t("activity.label.activityName") },
        { label: this.$t("activity.label.step") },
        { label: this.$t("activity.label.status") },
        { label: this.$t("activity.label.createdAt") },
      ],
      selectedDivision: null,
      selectedBU: null,
      isLoading: false,
      isDivisionLoading: false,
      isBusinessUnitLoading: false,
      isDownloading: false,
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

    exportAcctivityIcon() {
      return this.isDownloading ? "Spinner" : "DocumentDownload"
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
      return value === 1 ? "Created" : value === 2 ? "In Progress" : "Finished"
    },
    /**
     * get class property
     * @param value
     * @returns {string}
     */
    getActivityConfirmationStatusType(value) {
      return value === 1 ? "pill-disabled" : value === 2 ? "pill-c1" : "pill-cSuccess-inv"
    },
    getActivityStep(value) {
      return value === 1 ? "Basic info" : value === 2 ? "Remote access" : "Equipments"
    },
    getActivityConfirmationStepType(value) {
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
    // ==== PRE-LOAD ==========

    /**
     * Fetch Division
     */
    async fetchDivisions() {
      this.isDivisionLoading = true
      const divisionRes = await this.fetchDivisionList()

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
      const buRes = await this.tenantBusinessUnits()

      if (buRes && buRes.data) {
        this.businessUnits = buRes.data
      }

      this.isBusinessUnitLoading = false
    },
  },
}
</script>
