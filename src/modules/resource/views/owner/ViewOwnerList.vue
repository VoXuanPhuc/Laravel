<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("resource.owner.label") }}
        </EcHeadline>

        <!-- Add Owner -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddOwner">
          {{ $t("resource.owner.buttons.addOwner") }}
        </EcButton>
      </EcFlex>

      <!-- Search box -->
      <EcFlex class="flex-grow justify-end items-center w-full md:w-auto">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('resource.search')"
          class="w-full md:max-w-xs"
          @update:modelValue="onFilter"
          @clear-search="handleClearSearch"
        />
      </EcFlex>
    </EcFlex>

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="owners" class="mt-4 lg:mt-6">
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
              {{ getResourceOwnerFullName(item) }}
            </EcText>
          </RTableCell>

          <!-- Email -->
          <RTableCell>
            <EcText class="w-24">
              {{ item.email }}
            </EcText>
          </RTableCell>

          <!-- status -->
          <RTableCell>
            <EcText :variant="getOwnerInvitationStatusType(item.is_invite)" class="w-32">
              {{ getOwnerInvitationStatus(item.is_invite) }}
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
                  <EcText class="font-medium">{{ $t("resource.buttons.view") }}</EcText>
                </EcFlex> -->

                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditOwner(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("resource.buttons.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleOpenDeleteModal(item.uid, this.getResourceOwnerFullName(item))"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("resource.buttons.delete") }}</EcText>
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

    <!-- Actions -->
    <EcFlex class="">
      <EcButton variant="tertiary" @click="handleBackToResourceList">
        {{ $t("resource.owner.buttons.back") }}
      </EcButton>
    </EcFlex>
    <!-- Modal  delete owner -->
    <teleport to="#layer1">
      <ModalDeleteOwner
        :ownerUid="toDeleteOwnerUid"
        :ownerName="toDeleteOwnerName"
        :isModalDeleteOwnerOpen="isModalDeleteOpen"
        @handleCloseDeleteModal="handleCloseDeleteModal"
        @handleDeleteCallback="handleDeleteCallback"
      />
    </teleport>
  </RLayout>
</template>

<script>
import { useOwnerList } from "@/modules/resource/use/owner/useOwnerList"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import { ref } from "vue"
import ModalDeleteOwner from "../../components/ModalDeleteOwner.vue"

export default {
  name: "ViewOwnerList",
  setup() {
    const globalStore = useGlobalStore()
    const { getOwnerList, t, owners, totalItems, skip, limit, currentPage } = useOwnerList()
    const resourceCategories = ref([])
    return {
      globalStore,
      getOwnerList,
      t,
      owners,
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
        // { label: this.$t("resource.label.businessUnit") },
        { label: this.$t("resource.owner.labels.name") },
        { label: this.$t("resource.owner.labels.email") },
        { label: this.$t("resource.owner.labels.invitation_status") },
        { label: this.$t("resource.owner.labels.createdAt") },
      ],
      selectedCategory: "",
      searchQuery: "",
      onFilter: "",
      isLoading: false,
      isDownloading: false,
      isModalDeleteOpen: false,
      // Owner uid to delete
      toDeleteOwnerUid: null,
      toDeleteOwnerName: "",
    }
  },
  mounted() {
    this.fetchOwners()
  },
  computed: {
    /**
     * Format time
     */
    dateTimeFormat() {
      return this.globalStore.dateTimeFormat
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
    async fetchOwners() {
      this.isLoading = true
      const ownerRes = await this.getOwnerList()

      if (ownerRes && ownerRes.data) {
        this.owners = ownerRes.data
      }
      this.isLoading = false
    },
    /**
     * convert resource status to string status
     * @param value
     * @returns {string}
     */
    getOwnerInvitationStatus(value) {
      return value ? "Yes" : "No"
    },

    /**
     * get class property
     * @param value
     * @returns {string}
     */
    getOwnerInvitationStatusType(value) {
      return value ? "pill-cSuccess-inv" : "pill-disabled"
    },

    /**
     * Get Owner full name
     */
    getResourceOwnerFullName(owner) {
      const fullName = [owner?.first_name, owner?.last_name]
      return fullName.join(" ")
    },

    // Handle events

    /**
     * Add new activity
     */
    handleClickAddOwner() {
      goto("ViewOwnerNew")
    },

    /**
     *
     * @param {*} ownerUid
     */
    handleClickEditOwner(ownerUid) {
      goto("ViewOwnerDetail", {
        params: {
          uid: ownerUid,
        },
      })
    },

    /**
     *
     */
    handleClearSearch() {},

    /**
     * Open delete resource modal
     */
    handleOpenDeleteModal(ownerUid, ownerName) {
      this.toDeleteOwnerUid = ownerUid
      this.toDeleteOwnerName = ownerName
      this.isModalDeleteOpen = true
    },
    /**
     * Open delete resource modal
     */
    handleCloseDeleteModal() {
      this.toDeleteOwnerUid = null
      this.toDeleteOwnerName = ""
      this.isModalDeleteOpen = false
    },

    /**
     * Handle delete callback
     */
    handleDeleteCallback() {
      this.fetchOwners()
    },

    /**
     * Back to resource list
     */
    handleBackToResourceList() {
      goto("ViewResourceList")
    },
    // ==== PRE-LOAD ==========
  },
  components: { ModalDeleteOwner },
}
</script>
