<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <!-- Title -->
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("notification.logs.title") }}
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
    <!-- Sub Menu -->
    <EventNotificationSubMenu />

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="filteredNotificationLogs" class="mt-4 lg:mt-6">
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
            <EcText class="w-24"> {{ item.title }} </EcText>
          </RTableCell>

          <!-- Type -->
          <RTableCell> {{ item?.type?.toUpperCase() }}</RTableCell>

          <!-- Sent at -->
          <RTableCell>
            <EcText class="pr-5">
              {{ item.created_at }}
            </EcText>
          </RTableCell>

          <!-- Action -->
          <RTableCell :class="{ 'rounded-tr-lg': first, 'rounded-br-lg': last }">
            <EcButton
              variant="transparent"
              @click="handleClickViewNotificationLog(item.id)"
              v-tooltip="{ text: 'View Log detail' }"
            >
              <EcIcon class="hover:cursor-pointer" icon="Eye" />
            </EcButton>
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
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useGlobalStore } from "@/stores/global"
import { useNotification } from "../../use/useNotification"
import EventNotificationSubMenu from "../noti/EventNotificationSubMenu.vue"

export default {
  name: "ViewSettingList",
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
    const headerData = [
      { label: this.$t("notification.labels.title") },
      { label: this.$t("notification.labels.type") },
      { label: this.$t("notification.labels.sendAt") },
      // { label: this.$t("notification.labels.subject") },
      // { label: this.$t("notification.labels.desc") },
    ]
    return {
      searchQuery: "",
      filters,
      currentPage: 1,
      isLoading: false,
      headerData,
      recordLoading: [],
    }
  },
  setup() {
    const globalStore = useGlobalStore()
    const { eventNotificationLogs, getNotificationLogs } = useNotification()
    return {
      globalStore,
      eventNotificationLogs,
      getNotificationLogs,
    }
  },
  computed: {
    filteredNotificationLogs() {
      return this.eventNotificationLogs
    },
  },
  mounted() {
    this.fetchNotificationLogs()
  },

  methods: {
    /***
     * Fetch Event notifications
     */
    async fetchNotificationLogs() {
      this.isLoading = true
      const res = await this.getNotificationLogs(this.filters)
      if (res && res.data) {
        this.eventNotificationLogs = res.data
        // Pagination
        this.currentPage = res.current_page
        this.filters.page.per_page = res.per_page
        this.filters.page.total = res.total
      }
      this.isLoading = false
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
      this.fetchNotificationLogs()
    },
    /**
     * Clear search
     */
    handleClearSearch() {
      this.filters.filter = []
      this.filters.page = {}
      this.fetchNotificationLogs()
    },

    /**
     *
     */
    handleClickViewNotificationLog(uid) {
      goto("ViewNotificationLogDetail", {
        params: {
          uid: uid,
        },
      })
    },
    formatData() {},
  },
  watch: {
    currentPage() {
      this.filters.page.number = this.currentPage
      this.fetchNotificationLogs()
    },
  },
  components: { EventNotificationSubMenu },
}
</script>
