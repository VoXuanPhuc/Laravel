<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("resource.category.label") }}
        </EcHeadline>

        <!-- Add Category -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddCategory">
          {{ $t("resource.category.buttons.addCategory") }}
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
    <RTable :isLoading="isLoading" :list="categories" class="mt-4 lg:mt-6">
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
                  @click="handleClickEditCategory(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("resource.buttons.edit") }}</EcText>
                </EcFlex>

                <!-- Delete action -->
                <!-- <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleOpenDeleteModal(item.uid, this.getResourceOwnerFullName(item))"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("resource.buttons.delete") }}</EcText>
                </EcFlex> -->
                <!-- End delete -->
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

    <!-- Modal  delete owner -->
    <teleport to="#layer1">
      <ModalAddNewCategory
        :isModalAddNewCategoryOpen="isModalAddNewCategoryOpen"
        @handleCloseAddNewCategoryModal="handleCloseAddNewCategoryModal"
        @handleCallbackAddNewCategory="handleCallbackAddNewCategory"
      />
    </teleport>
  </RLayout>
</template>

<script>
import { useCategoryList } from "@/modules/resource/use/category/useCategoryList"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import ModalAddNewCategory from "../../components/ModalAddNewCategory.vue"

export default {
  name: "ViewCategoryList",
  setup() {
    const globalStore = useGlobalStore()
    const { getResourceCategoryList, t, categories, totalItems, skip, limit, currentPage } = useCategoryList()

    return {
      globalStore,
      getResourceCategoryList,
      t,
      skip,
      limit,
      currentPage,
      totalItems,
      categories,
    }
  },
  data() {
    return {
      headerData: [
        // { label: this.$t("resource.label.businessUnit") },
        { label: this.$t("resource.category.labels.name") },
        { label: this.$t("resource.category.labels.description") },
        { label: this.$t("resource.category.labels.createdAt") },
      ],
      selectedCategory: "",
      searchQuery: "",
      onFilter: "",
      isLoading: false,
      isModalAddNewCategoryOpen: false,
    }
  },
  mounted() {
    this.fetchResourceCategories()
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
    async fetchResourceCategories() {
      this.isLoading = true
      const categoryRes = await this.getResourceCategoryList()

      if (categoryRes) {
        this.categories = categoryRes
      }
      this.isLoading = false
    },

    // Handle events

    /**
     * Add new Category
     */
    handleClickAddCategory() {
      this.isModalAddNewCategoryOpen = true
    },

    /**
     * Cancel add Category
     */
    handleCloseAddNewCategoryModal() {
      this.isModalAddNewCategoryOpen = false
    },

    /**
     * Create category callback
     */
    handleCallbackAddNewCategory() {
      this.fetchResourceCategories()
    },

    /**
     *
     * @param {*} categoryUid
     */
    handleClickEditCategory(categoryUid) {
      goto("ViewCategoryDetail", {
        params: {
          uid: categoryUid,
        },
      })
    },

    /**
     *
     */
    handleClearSearch() {},

    // ==== PRE-LOAD ==========
  },
  components: { ModalAddNewCategory },
}
</script>
