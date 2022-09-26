<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("industry.industry") }}
        </EcHeadline>
        <EcButton class="mb-3 lg:mb-0" iconPrefix="PlusCircle" variant="primary-sm" @click="handleClickCreateIndustry">
          {{ $t("industry.add") }}
        </EcButton>
      </EcFlex>
      <!-- Search box -->
      <EcFlex class="flex-grow justify-end items-center w-full md:w-auto">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('industry.search')"
          class="w-full md:max-w-xs"
          @update:modelValue="onFilter"
          @search="industryListFiltered"
          @clear-search="handleClearSearch"
        />
      </EcFlex>
    </EcFlex>

    <!-- Industry List -->

    <!-- Table -->
    <RTable :isLoading="isLoading" :list="industryListFiltered" class="mt-2 lg:mt-4">
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

          <!-- status -->
          <RTableCell>
            <EcText :variant="getIndustryStatusClass(item.is_active)" class="w-32">
              {{ getIndustryStatus(item.is_active) }}
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
                <!-- Edit action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickUpdateIndustry(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("industry.button.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleOpenDeleteModal(item.uid, item.name)"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("industry.button.delete") }}</EcText>
                </EcFlex>
              </RTableAction>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>

    <!--    Pagination-->
    <EcFlex class="mt-8 flex-col sm:mt-12 sm:flex-row" variant="basic">
      <RPaginationStatus :currentPage="currentPage" :limit="limit" :totalCount="totalItems" class="mb-4 sm:mb-0" />
      <RPagination v-model="currentPage" :itemPerPage="limit" :totalItems="totalItems" />
    </EcFlex>
  </RLayout>

  <!-- Modals -->
  <teleport to="#layer2">
    <!-- Modal Delete -->
    <EcModalSimple :isVisible="isModalDeleteOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
      <EcBox class="text-center">
        <EcFlex class="justify-center">
          <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
        </EcFlex>

        <!-- Messages -->
        <EcBox>
          <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
            {{ $t("industry.confirmToDelete") }}
          </EcHeadline>
          <!-- Industry name -->
          <EcText class="text-lg"> {{ deleteIndustryName }} </EcText>
          <EcText class="text-c0-500 mt-4">
            {{ $t("industry.confirmDeleteQuestion") }}
          </EcText>
        </EcBox>

        <!-- Actions -->
        <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
          <EcButton variant="warning" @click="handleClickDeleteBtn(deleteIndustryUid)">
            {{ $t("industry.delete") }}
          </EcButton>
          <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
            {{ $t("industry.cancel") }}
          </EcButton>
        </EcFlex>
        <EcFlex v-else class="items-center justify-center mt-10 h-10">
          <EcSpinner type="dots" />
        </EcFlex>
      </EcBox>
    </EcModalSimple>
  </teleport>
</template>

<script>
import { useIndustryList } from "@/modules/industry/use/industry/useIndustryList"
import { formatData, goto } from "@/modules/core/composables"
import { useGlobalStore } from "@/stores/global"
import { ref } from "vue"
import { useIndustryDetail } from "@/modules/industry/use/industry/useIndustryDetail"

export default {
  name: "ViewIndustryList",
  data() {
    return {
      headerData: [
        // { label: this.$t("activity.label.businessUnit") },
        { label: this.$t("industry.label.industryName") },
        { label: this.$t("industry.label.status") },
        { label: this.$t("industry.label.createdAt") },
      ],
      searchQuery: "",
      isLoading: false,
      deleteIndustryUid: null,
      deleteIndustryName: null,
      isModalDeleteOpen: false,
      isDeleteLoading: false,
    }
  },
  computed: {
    dateTimeFormat() {
      return this.globalStore.dateTimeFormat
    },
    /**
     * Filter Industry list by name
     */
    industryListFiltered() {
      console.log(this.searchQuery)
      if (this.searchQuery !== "") {
        return this.industryList.filter((industryItem) => {
          return industryItem.name.toUpperCase().match(this.searchQuery.toUpperCase())
        })
      }

      return this.industryList
    },
  },

  setup() {
    const { getIndustryList, totalItems, skip, limit, currentPage } = useIndustryList()
    const { industries, deleteIndustry } = useIndustryDetail()
    const globalStore = useGlobalStore()
    const industryList = ref([])

    return {
      globalStore,
      deleteIndustry,
      industries,
      getIndustryList,
      industryList,
      totalItems,
      skip,
      limit,
      currentPage,
    }
  },

  mounted() {
    this.fetchIndustry()
  },

  methods: {
    formatData,
    /**
     * convert Industry status to string status
     * @param value
     * @returns {string}
     */
    getIndustryStatus(value) {
      return value === 1 ? "Active" : "Inactive"
    },
    /**
     * get class property
     * @param value
     * @returns {string}
     */
    getIndustryStatusClass(value) {
      return value === 1 ? "pill-cSuccess-inv" : "pill-disabled"
    },
    /**
     * Get Industry list
     */
    async fetchIndustry() {
      this.isLoading = true
      this.industryList = await this.getIndustryList()
      this.isLoading = false
    },
    /**
     * Go to create Industry
     */
    handleClickCreateIndustry() {
      this.$router.push({
        name: "AddIndustryNew",
      })
    },
    /**
     * Go to update Industry
     */
    handleClickUpdateIndustry(industryUid) {
      goto("ViewIndustryDetail", {
        params: {
          industryUid,
        },
      })
    },
    /**
     * Open Modal confirm Delete Industry
     * @param industryUid
     * @param industryName
     */
    handleOpenDeleteModal(industryUid, industryName) {
      this.isModalDeleteOpen = true
      this.deleteIndustryUid = industryUid
      this.deleteIndustryName = industryName
    },
    /** Close delete modal */
    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
      this.confirmedOrganizationName = ""
    },
    /**
     * Delete Industry
     * @param industryUid
     */
    async handleClickDeleteBtn(industryUid) {
      this.isDeleteLoading = true
      await this.deleteIndustry(industryUid)
      this.isDeleteLoading = false
      this.$emit("handleDeletedIndustry")
      this.fetchIndustry()
      this.handleCloseDeleteModal()
    },
    /** Clear Search **/
    handleClearSearch() {
      this.searchQuery = ""
    },
  },
}
</script>
