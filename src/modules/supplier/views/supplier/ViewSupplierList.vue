<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="flex-wrap items-center">
      <EcFlex class="flex-wrap items-center justify-between w-full lg:w-auto lg:mr-4">
        <EcHeadline class="mb-3 mr-4 text-cBlack lg:mb-0">
          {{ $t("supplier.suppliers") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>
    <!-- action button -->
    <EcBox class="flex justify-end mt-8 lg:mt-16">
      <EcBox class="grid grid-cols-1 sm:grid-cols-3 gap-2 flex justify-end">
        <!--- export supplier -->
        <EcButton :iconPrefix="exportSupplierIcon" class="mb-3 lg:mb-0" variant="primary-sm" @click="handleClickDownloadSupplier">
          {{ $t("supplier.buttons.exportSuppliers") }}
        </EcButton>

        <!-- View Supplier Category -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="Resource" variant="primary-sm" @click="handleClickViewCategories">
          {{ $t("supplier.buttons.viewSupplierCategories") }}
        </EcButton>

        <!-- Add supplier -->
        <EcButton class="mb-3 lg:mb-0" iconPrefix="plus-circle" variant="primary-sm" @click="handleClickAddSupplier">
          {{ $t("supplier.buttons.addSupplier") }}
        </EcButton>
      </EcBox>
    </EcBox>
    <!-- Table -->
    <RTable :isLoading="isLoading" :list="suppliers" class="mt-2 lg:mt-4 overflow-x-scroll">
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


          <!-- desc -->
          <RTableCell>
            <EcText class="w-32">
              {{ item.email }}
            </EcText>
          </RTableCell>

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
                  @click="handleClickEditSupplier(item.uid)"
                >
                  <EcIcon class="mr-3" icon="Pencil" />
                  <EcText class="font-medium">{{ $t("supplier.buttons.edit") }}</EcText>
                </EcFlex>
                <!-- Delete action -->
                <EcFlex
                  class="items-center px-4 py-2 cursor-pointer text-cError-500 hover:bg-c0-100"
                  @click="handleClickDeleteSupplier(item)"
                >
                  <EcIcon class="mr-3" icon="X" />
                  <EcText class="font-medium">{{ $t("supplier.buttons.delete") }}</EcText>
                </EcFlex>
              </RTableAction>
            </EcFlex>
          </RTableCell>
        </RTableRow>
      </template>
    </RTable>
  </RLayout>

  <!-- Modal Delete -->
  <teleport to="#layer1">
    <ModalConfirmDeleteSupplier
      :isModalDeleteOpen="isModalDeleteOpen"
      :supplier="supplierToDelete"
      @handleCallBackDeletedModal="handleCallBackDeletedModal"
      @handleCloseDeleteModal="handleCloseDeleteModal"
      @overlay-click="handleCloseDeleteModal"
    >
    </ModalConfirmDeleteSupplier>
  </teleport>
</template>
<script>
import { useSupplierList } from "@/modules/supplier/use/supplier/useSupplierList"
import { formatData, goto } from "@/modules/core/composables"
import ModalConfirmDeleteSupplier from "@/modules/supplier/components/ModalConfirmDeleteSupplier"

export default {
  name: "ViewSupplierApplication",
  data() {
    return {
      headerData: [
        { label: this.$t("supplier.labels.supplierName") },
        { label: this.$t("supplier.labels.contactPoint") },
        { label: this.$t("supplier.labels.createdAt") },
      ],
      isLoading: false,
      isDownloading: false,
      isModalDeleteOpen: false,
      supplierToDelete: {},
    }
  },
  setup() {
    const { downloadSuppliers, globalStore, suppliers, getSuppliers, t, totalItems, skip, limit, currentPage } = useSupplierList()

    return {
      downloadSuppliers,
      globalStore,
      suppliers,
      getSuppliers,
      t,
      skip,
      limit,
      currentPage,
      totalItems,
    }
  },

  mounted() {
    this.fetchSuppliers()
  },

  computed: {
    dateTimeFormat() {
      return this.globalStore.dateTimeFormat
    },

    exportSupplierIcon() {
      return this.isDownloading ? "Spinner" : "DocumentDownload"
    },
  },
  methods: {
    formatData,

    /**
     * fetch suppliers
     * @returns {Promise<void>}
     */
    async fetchSuppliers() {
      this.isLoading = true
      const supplierRes = await this.getSuppliers()
      // console.log(supplierRes)
      this.suppliers = supplierRes

      if (supplierRes && supplierRes.data) {
        this.suppliers = supplierRes.data
      }
      this.isLoading = false
    },

    handleClickAddSupplier() {
      goto("ViewSupplierNew")
    },

    handleClickEditSupplier(supplierUid) {
      goto("ViewSupplierDetail", {
        params: {
          uid: supplierUid,
        },
      })
    },

    handleClickDeleteSupplier(supplier) {
      this.supplierToDelete = supplier
      this.isModalDeleteOpen = true
    },

    /**
     * click to download supplier
     */
    async handleClickDownloadSupplier() {
      this.isDownloading = true
      await this.downloadSuppliers()
      this.isDownloading = false
    },

    /**
     * Open confirm delete modal
     */
    handleOpenDeleteModal() {
      this.isModalDeleteOpen = true
    },

    /**
     * Open cancel Add new Category modal
     */
    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
    },

    /**
     * Callback after add new category
     */
    handleCallBackDeletedModal() {
      this.fetchSuppliers()
      this.handleCloseDeleteModal()
    },

    /**
     * View supplier category list
     */
    handleClickViewCategories() {
      goto("ViewSupplierCategoryList")
    },
  },

  components: { ModalConfirmDeleteSupplier },
}
</script>
