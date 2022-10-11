<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("supplier.category.label") }}
        </EcHeadline>

        <!-- Add Category -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddCategory">
          {{ $t("supplier.category.buttons.addCategory") }}
        </EcButton>
      </EcFlex>

      <!-- Search box -->
      <EcFlex class="flex-grow justify-end items-center w-full md:w-auto">
        <RSearchBox
          v-model="searchQuery"
          :isSearched="searchQuery !== ''"
          :placeholder="$t('supplier.search')"
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
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-c1-500 hover:bg-c0-100"
                  @click="handleClickEditCategory(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("supplier.buttons.edit") }}</EcText>
                </EcFlex>
              </RTableAction>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>

    <!-- Actions -->
    <EcFlex class="mt-10">
      <EcButton variant="tertiary" @click="handleBackToSupplierList">
        {{ $t("supplier.category.buttons.back") }}
      </EcButton>
    </EcFlex>
    <!-- Modal  delete owner -->
    <teleport to="#layer1">
      <ModalAddNewCategory
        :isModalAddNewCategoryOpen="isModalAddNewCategoryOpen"
        @handleCloseAddNewCategoryModal="handleCloseAddNewCategoryModal"
        @handleCallBackAddNewCategory="handleCallbackAddNewCategory"
      />
    </teleport>
  </RLayout>
</template>

<script>
import { useCategoryList } from "@/modules/supplier/use/category/useCategoryList"
import { useGlobalStore } from "@/stores/global"
import { formatData, goto } from "@/modules/core/composables"
import ModalAddNewCategory from "../../components/ModalAddNewCategory.vue"

export default {
  name: "ViewCategoryList",
  setup() {
    const globalStore = useGlobalStore()
    const { getSupplierCategories, categories } = useCategoryList()

    return {
      globalStore,
      getSupplierCategories,
      categories,
    }
  },
  data() {
    return {
      headerData: [
        { label: this.$t("supplier.category.labels.name") },
        { label: this.$t("supplier.category.labels.description") },
        { label: this.$t("supplier.category.labels.createdAt") },
      ],
      selectedCategory: "",
      searchQuery: "",
      onFilter: "",
      isLoading: false,
      isModalAddNewCategoryOpen: false,
    }
  },
  mounted() {
    this.fetchSupplierCategories()
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
    async fetchSupplierCategories() {
      this.isLoading = true
      const categoryRes = await this.getSupplierCategories()

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
      this.fetchSupplierCategories()
    },

    /**
     * Back to supplier list
     */
    handleBackToSupplierList() {
      goto("ViewSupplierList")
    },

    /**
     *
     * @param {*} categoryUid
     */
    handleClickEditCategory(categoryUid) {
      goto("ViewSupplierCategoryDetail", {
        params: {
          uid: categoryUid,
        },
      })
    },

    /**
     *
     */
    handleClearSearch() {},
  },
  components: { ModalAddNewCategory },
}
</script>
