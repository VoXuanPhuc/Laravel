<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <!-- Title -->
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("notification.labels.notiTemplates") }}
        </EcHeadline>

        <EcButton variant="primary" iconPrefix="plus-circle" @click="handleClickAddEventNotification">
          {{ $t("notification.labels.add") }}
        </EcButton>
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

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="filteredNotificationTemplates" class="mt-4 lg:mt-6">
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
            <EcText class="w-24"> {{ item.name }} </EcText>
          </RTableCell>

          <!-- Type -->
          <RTableCell> {{ item?.type?.toUpperCase() }}</RTableCell>

          <!-- Methods -->
          <RTableCell>
            <EcText class="pr-5">
              {{ item.methods?.join(", ")?.toUpperCase() }}
            </EcText>
          </RTableCell>

          <!-- Subject -->
          <RTableCell>
            <EcText class="pr-5 truncate">
              {{ item.title }}
            </EcText>
          </RTableCell>

          <!-- Desc -->
          <RTableCell>
            <EcText class="pr-5 truncate">
              {{ item.description }}
            </EcText>
          </RTableCell>

          <!-- Action -->
          <RTableCell :class="{ 'rounded-tr-lg': first, 'rounded-br-lg': last }" :isTruncate="false" variant="gradient">
            <EcFlex class="items-center justify-center h-full">
              <RTableAction class="w-30" :isLoading="recordLoading[item.uid]">
                <!-- View action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cBlack hover:bg-c0-100"
                  @click="handleClickExportEventNotiRecord(item.uid)"
                >
                  <EcIcon class="mr-3" icon="DocumentDownload" />
                  <EcText class="font-medium">{{ $t("notification.buttons.export") }}</EcText>
                </EcFlex>

                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditEventNotification(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("notification.buttons.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleOpenDeleteModal(item.uid, item.name)"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("notification.buttons.delete") }}</EcText>
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
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useGlobalStore } from "@/stores/global"
import { useEventNotificationList } from "../../use/noti/useEventNotificationList"

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
      { label: this.$t("notification.labels.name") },
      { label: this.$t("notification.labels.type") },
      { label: this.$t("notification.labels.methods") },
      { label: this.$t("notification.labels.subject") },
      { label: this.$t("notification.labels.desc") },
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
    const { eventNotificationList, getNotificationTemplateList } = useEventNotificationList()

    return {
      globalStore,
      eventNotificationList,
      getNotificationTemplateList,
    }
  },

  computed: {
    filteredNotificationTemplates() {
      return this.eventNotificationList
    },
  },

  mounted() {
    this.fetchEventNotificationTemplates()
  },
  methods: {
    /***
     * Fetch Event notifications
     */
    async fetchEventNotificationTemplates() {
      this.isLoading = true

      const res = await this.getNotificationTemplateList(this.filters)

      if (res && res.data) {
        this.eventNotificationList = res.data

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
      this.fetchEventNotificationTemplates()
    },

    /**
     * Clear search
     */
    handleClearSearch() {
      this.filters.filter = []
      this.filters.page = {}

      this.fetchEventNotificationTemplates()
    },

    /**
     * Add Template
     */
    handleClickAddEventNotification() {
      goto("ViewEventNotificationNew")
    },

    /**
     *
     */
    handleClickEditEventNotification(uid) {
      goto("ViewEventNotificationDetail", {
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
      this.fetchEventNotificationTemplates()
    },
  },
}
</script>
