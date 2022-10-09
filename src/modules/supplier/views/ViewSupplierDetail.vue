<template>
  <RLayout>
    <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
      {{ supplierName }}
    </EcHeadline>
    <RLayoutTwoCol :isLoading="isLoading">
      <template #left>
        <EcBox class="width-full px-4 sm:px-10" variant="card-1">
          <EcText class="mb-4 font-bold text-lg">{{ $t("supplier.title.supplierDetail") }}</EcText>

          <!-- Name -->
          <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="supplier.name"
              :label="$t('supplier.name')"
              :validator="supplierValidator$"
              componentName="EcInputText"
              field="supplier.name"
              @input="supplierValidator$.supplier.name.$touch()"
            />
            <!-- error message name has been used -->
            <EcBox v-if="isNameUnique" class="mt-2">
              <EcText class="text-cError-600 text-sm mt-1"> {{ $t("supplier.nameUnique") }} </EcText>
            </EcBox>
          </EcBox>

          <!-- Status -->
          <EcFlex class="flex-wrap max-w-md">
            <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
              <RFormInput
                v-model="supplier.is_active"
                :label="$t('supplier.labels.active')"
                :labelFalse="$t('supplier.labels.no')"
                :labelTrue="$t('supplier.labels.yes')"
                componentName="EcToggle"
                showLabel
              />
            </EcBox>
          </EcFlex>

          <!-- description -->
          <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="supplier.description"
              :label="$t('supplier.desc')"
              :rows="2"
              :validator="supplierValidator$"
              componentName="EcInputLongText"
              field="supplier.description"
              @input="supplierValidator$.supplier.description.$touch()"
            />
          </EcBox>

          <!-- Supplier Category -->
          <EcFlex class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
            <EcBox class="w-full mb-6">
              <EcFlex>
                <EcText class="mb-3">{{ $t("supplier.category.label") }}</EcText>
                <EcButton
                  class="h-6 ml-2"
                  title="Did not see category? Add new"
                  variant="primary-rounded"
                  @click="handleOpenAddNewCategoryModal"
                >
                  <EcIcon class="h-6 w-4" icon="Plus" />
                </EcButton>
              </EcFlex>
              <EcFlex>
                <EcMultiSelect
                  v-model="supplier.categories"
                  :options="categories"
                  :valueKey="'uid'"
                  @input="supplierValidator$.supplier.categories"
                />
                <EcSpinner v-if="isLoadingCategories"></EcSpinner>
              </EcFlex>
            </EcBox>
          </EcFlex>
        </EcBox>

        <EcBox class="width-full px-4 mt-8 sm:px-10" variant="card-1">
          <EcText class="mb-4 font-bold text-lg">Contact point</EcText>
          <!-- email -->
          <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="supplier.email"
              :label="$t('supplier.email')"
              :validator="supplierValidator$"
              componentName="EcInputText"
              field="supplier.email"
              @input="supplierValidator$.supplier.email.$touch()"
            />
          </EcBox>

          <!-- phone -->
          <EcBox class="w-1/2 mb-6">
            <RFormInput
              v-model="supplier.phone_number"
              :label="$t('supplier.phone')"
              :validator="supplierValidator$"
              componentName="EcInputText"
              field="supplier.phone_number"
              @input="supplierValidator$.supplier.phone_number.$touch()"
            />
          </EcBox>

          <!-- taxCode -->
          <EcBox class="w-1/2 mb-6">
            <RFormInput
              v-model="supplier.fax"
              :label="$t('supplier.fax')"
              :validator="supplierValidator$"
              componentName="EcInputText"
              field="supplier.fax"
              @input="supplierValidator$.supplier.fax.$touch()"
            />
          </EcBox>

          <!-- address -->
          <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="supplier.address"
              :label="$t('supplier.address')"
              :validator="supplierValidator$"
              componentName="EcInputText"
              field="supplier.address"
              @input="supplierValidator$.supplier.address.$touch()"
            />
          </EcBox>
        </EcBox>

        <!-- Actions -->
        <EcFlex v-if="!isUpdating" class="width-full mt-8 px-4 sm:px-10">
          <EcButton variant="primary" @click="handleClickUpdateSupplier">
            {{ $t("supplier.buttons.update") }}
          </EcButton>
          <EcButton class="ml-3" variant="tertiary-outline" @click="handleClickCancel">
            {{ $t("supplier.buttons.cancel") }}
          </EcButton>
        </EcFlex>
        <EcFlex v-else class="items-center justify-center mt-10 h-10">
          <EcSpinner type="dots" />
        </EcFlex>
      </template>

      <template #right>
        <EcBox class="width-full px-4 mb-8" variant="card-1">
          <!-- certificate -->
          <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6 mt-4">
            <EcFlex class="flex-wrap w-full max-w-full">
              <EcBox class="w-full mb-6 sm:pr-6">
                <RUploadFiles
                  :dir="'supplier/certificates'"
                  :documentTitle="title"
                  :fileListAfterUpload="fileListAfterUpload"
                  :isUploadOnSelect="false"
                  :maxFileNum="10"
                  dropZoneCls="border-c0-500 border-dashed border-2 bg-cWhite p-2 md:py-4"
                  @handleSignleFileUploaded="handleCertificateUploaded"
                />
              </EcBox>
            </EcFlex>

            <RTable
              v-if="certificateUrls.length > 0"
              :isLoading="isCertificateLoading"
              :list="certificateUrls"
              class="mt-2 lg:mt-4"
            >
              <template #header>
                <RTableHeaderRow>
                  <RTableHeaderCell v-for="(h, idx) in headerCertificate" :key="idx" class="text-cBlack">
                    {{ h.label }}
                  </RTableHeaderCell>
                </RTableHeaderRow>
              </template>

              <template v-slot="{ item }">
                <RTableRow class="hover:bg-c0-100">
                  <!-- name file -->
                  <RTableCell class="hover:text-sky-500">
                    <EcText class="cursor-pointer truncate w-36">
                      <a :href="item.cert_path" target="_blank">
                        {{ getOriginalFileName(item.cert_path) }}
                      </a>
                    </EcText>
                  </RTableCell>

                  <!-- action -->
                  <RTableCell>
                    <!-- Delete action -->
                    <EcFlex
                      class="w-full items-end cursor-pointer text-cError-500 hover:bg-c0-100"
                      @click="handleOpenCertificateDeleteModal(item)"
                    >
                      <EcIcon class="mr-3" icon="X" />
                    </EcFlex>
                  </RTableCell>
                </RTableRow>
              </template>
            </RTable>
          </EcBox>
        </EcBox>

        <!-- delete supplier-->
        <EcBox class="width-full px-4" variant="card-1">
          <EcHeadline as="h2" class="text-c1-800 px-5" variant="h2">
            {{ $t("supplier.deleteSupplier") }}
          </EcHeadline>
          <EcText class="px-5 my-6 text-c0-500 leading-normal">
            {{ $t("supplier.deleteFullNote") }}
          </EcText>
          <EcButton class="mx-5" iconPrefix="Trash" variant="warning" @click="handleOpenSupplierDeleteModal">
            {{ $t("supplier.buttons.deleteSupplier") }}
          </EcButton>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <!-- Modal add new resource category -->
    <teleport to="#layer1">
      <ModalAddNewCategory
        :isModalAddNewCategoryOpen="isModalAddNewCategoryOpen"
        @handleCallBackAddNewCategory="handleCallBackAddNewCategory"
        @handleCloseAddNewCategoryModal="handleCloseAddNewCategoryModal"
        @overlay-click="handleCloseAddNewCategoryModal"
      />
    </teleport>

    <!-- Modal Delete -->
    <teleport to="#layer2">
      <ModalConfirmDeleteSupplier
        :isModalDeleteOpen="isSupplierModalDeleteOpen"
        :supplier="this.supplier"
        @handleCallBackDeletedModal="handleSupplierCallBackDeletedModal"
        @handleCloseDeleteModal="handleCloseSupplierDeleteModal"
        @overlay-click="handleCloseSupplierDeleteModal"
      >
      </ModalConfirmDeleteSupplier>
    </teleport>

    <!-- Modal Certificate Delete -->
    <teleport to="#layer3">
      <ModalConfirmDeleteCertificate
        :certificate="certificateToDelete"
        :isModalDeleteOpen="isCertificateModalDeleteOpen"
        :supplierUid="this.supplier.uid"
        @handleCallBackDeletedModal="handleCertificateCallBackDeletedModal"
        @handleCloseDeleteModal="handleCloseCertificateDeleteModal"
        @overlay-click="handleCloseCertificateDeleteModal"
      >
      </ModalConfirmDeleteCertificate>
    </teleport>
  </RLayout>
</template>

<script>
import { goto } from "@/modules/core/composables"
import { useSupplierDetail } from "@/modules/supplier/use/supplier/useSupplierDetail"
import { useCategoryList } from "@/modules/supplier/use/category/useCategoryList"
import ModalAddNewCategory from "@/modules/supplier/components/ModalAddNewCategory"
import ModalConfirmDeleteSupplier from "@/modules/supplier/components/ModalConfirmDeleteSupplier"
import { useCertificateList } from "@/modules/supplier/use/certificate/useCertificateList"
import { useCertificateNew } from "@/modules/supplier/use/certificate/useCertificateNew"
import ModalConfirmDeleteCertificate from "@/modules/supplier/components/ModalConfirmDeleteCertificate"

export default {
  name: "ViewSupplierDetail",
  data() {
    return {
      isUpdating: false,
      isNameUnique: false,
      isLoading: false,
      isLoadingCategories: false,
      isUploading: false,
      isCertificateLoading: false,

      title: "Certificate of Assurance",
      isModalAddNewCategoryOpen: false,
      isSupplierModalDeleteOpen: false,
      isCertificateModalDeleteOpen: false,

      certificateToDelete: {},
      supplierName: "",
      certificateUrls: [],

      fileListAfterUpload: [],

      headerCertificate: [{ label: this.$t("supplier.certificate.certificateName") }],
    }
  },
  setup() {
    const { supplier, supplierValidator$, getDetailSupplier, updateSupplier } = useSupplierDetail()

    const { categories, getSupplierCategories } = useCategoryList()

    const { certificate, uploadCertificate } = useCertificateNew()

    const { getCertificateListBySupplier } = useCertificateList()

    return {
      supplier,
      supplierValidator$,
      getDetailSupplier,
      updateSupplier,

      getSupplierCategories,
      categories,

      certificate,
      uploadCertificate,

      getCertificateListBySupplier,
    }
  },

  props: {
    uid: {
      type: String,
      require: true,
    },
  },

  mounted() {
    this.fetchSupplier()
    this.fetchSupplierCategories()
  },

  methods: {
    /**
     * fetch vendor categories
     * @returns {Promise<void>}
     */
    async fetchSupplierCategories() {
      this.isLoadingCategories = true
      this.categories = await this.getSupplierCategories()
      this.isLoadingCategories = false
    },

    /**
     * fetch detail supplier
     * @returns {Promise<void>}
     */
    async fetchSupplier() {
      this.isLoading = true
      const response = await this.getDetailSupplier(this.uid)

      if (response && response.uid) {
        this.supplier = response
        this.supplierName = this.supplier.name
        // get certificate
        await this.fetchCertificates(this.uid)
      }
      this.isLoading = false
    },

    /**
     * @param supplierUid
     * @returns {Promise<void>}
     */
    async fetchCertificates(supplierUid) {
      const res = await this.getCertificateListBySupplier(supplierUid)
      if (res) {
        this.certificateUrls = res
      }
    },

    /**
     * update category
     */
    async handleClickUpdateSupplier() {
      this.supplierValidator$.$touch()

      if (this.supplierValidator$.supplier.$invalid) {
        return
      }
      this.isUpdating = true

      // save certificate
      if (this.certificate.certs.length > 0) {
        await this.createSupplierCertificate(this.supplier.uid, this.certificate)
      }

      const updateSupplierRes = await this.updateSupplier(this.supplier.uid, this.supplier)
      if (updateSupplierRes && updateSupplierRes.uid) {
        this.supplier = updateSupplierRes
      }

      this.isUpdating = false
    },

    /**
     * create new certificate for supplier
     */
    async createSupplierCertificate(supplierUid, certificate) {
      await this.uploadCertificate(supplierUid, certificate)
      await this.fetchCertificates(this.supplier.uid)
      this.certificate.certs = []
      this.fileListAfterUpload = []
    },

    /** Handle uploaded logo */
    handleCertificateUploaded(result) {
      this.certificate.certs.push(result.url)
    },

    // go to view supplier list
    handleClickCancel() {
      goto("ViewSupplierList")
    },

    /**
     * Open addNewCategory
     */
    handleOpenAddNewCategoryModal() {
      this.isModalAddNewCategoryOpen = true
    },

    /**
     * Open cancel Add new Category modal
     */
    handleCloseAddNewCategoryModal() {
      this.isModalAddNewCategoryOpen = false
    },

    /**
     * Callback after add new category
     */
    handleCallBackAddNewCategory() {
      this.fetchSupplierCategories()
    },

    /**
     * Open confirm delete modal
     */
    handleOpenSupplierDeleteModal() {
      this.isSupplierModalDeleteOpen = true
    },

    /**
     * Open cancel Add new Category modal
     */
    handleCloseSupplierDeleteModal() {
      this.isSupplierModalDeleteOpen = false
    },

    /**
     * Callback after delete supplier
     */
    handleSupplierCallBackDeletedModal() {
      goto("ViewSupplierList")
    },

    /**
     * get original fileName
     * @param url
     * @returns {string}
     */
    getOriginalFileName(url) {
      let fileName = ""
      for (let i = url.length - 1; i >= 0; i--) {
        if (url[i] === "/") break
        fileName = url[i] + fileName
      }
      return fileName
    },

    handleOpenCertificateDeleteModal(certificate) {
      this.certificateToDelete = certificate
      this.isCertificateModalDeleteOpen = true
    },

    async handleCertificateCallBackDeletedModal() {
      await this.fetchCertificates(this.supplier.uid)
      this.isCertificateModalDeleteOpen = false
    },

    handleCloseCertificateDeleteModal() {
      this.isCertificateModalDeleteOpen = false
    },
  },

  components: { ModalAddNewCategory, ModalConfirmDeleteSupplier, ModalConfirmDeleteCertificate },
}
</script>
