<template>
  <RLayout>
    <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
      {{ $t("supplier.title.newSupplier") }}
    </EcHeadline>
    <RLayoutTwoCol>
      <template #left>
        <EcBox class="width-full sm:px-10" variant="card-1">
          <EcText class="mb-4 font-bold text-lg"> {{ $t("supplier.title.supplierDetail") }}</EcText>
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
          </EcBox>

          <!-- Status -->
          <EcFlex class="flex-wrap max-w-md">
            <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
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
          <EcFlex class="w-full 2xl:w-6/12 mb-4 sm:pr-6">
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
                <EcSpinner v-if="isLoadingCategories" class="ml-2"></EcSpinner>
              </EcFlex>
            </EcBox>
          </EcFlex>
        </EcBox>

        <EcBox class="width-full mt-8 px-4 sm:px-10" variant="card-1">
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
      </template>

      <template #right>
        <EcBox class="width-full px-4" variant="card-1">
          <EcHeadline as="h3" class="text-c1-800 px-5" variant="h3">
            {{ $t("supplier.title.upload") }}
          </EcHeadline>
          <EcBox class="width-full mt-4">
            <!-- certificate -->
            <EcFlex class="flex-wrap w-full max-w-full">
              <EcBox class="w-full mb-6 sm:pr-6">
                <RUploadFiles
                  :dir="'supplier/certificates'"
                  :documentTitle="$t('supplier.certificate.title')"
                  :isUploadOnSelect="false"
                  :maxFileNum="10"
                  :isParentSubmitting="isFormSubmitting"
                  dropZoneCls="border-c0-500 border-dashed border-2 bg-cWhite p-2 md:py-4"
                  @handleSingleUploadResult="handleCertificateUploaded"
                  @handleBulkFilesUpload="createSupplierCertificate"
                />
              </EcBox>
            </EcFlex>
          </EcBox>
        </EcBox>
      </template>
    </RLayoutTwoCol>
    <!-- Actions -->
    <EcFlex v-if="!isCreating" class="width-full mt-6 px-4 sm:px-10">
      <EcButton variant="primary" @click="handleClickCreateSupplier">
        {{ $t("supplier.buttons.create") }}
      </EcButton>
      <EcButton class="ml-3" variant="tertiary-outline" @click="handleClickCancel">
        {{ $t("supplier.buttons.cancel") }}
      </EcButton>
    </EcFlex>
    <EcFlex v-else class="items-center justify-center mt-10 h-10">
      <EcSpinner type="dots" />
    </EcFlex>
    <!-- Modal add new resource category -->
    <teleport to="#layer1">
      <ModalAddNewCategory
        :isModalAddNewCategoryOpen="isModalAddNewCategoryOpen"
        @handleCallBackAddNewCategory="handleCallBackAddNewCategory"
        @handleCloseAddNewCategoryModal="handleCloseAddNewCategoryModal"
        @overlay-click="handleCloseAddNewCategoryModal"
      />
    </teleport>
  </RLayout>
</template>

<script>
import { goto } from "@/modules/core/composables"
import { useSupplierNew } from "@/modules/supplier/use/supplier/useSupplierNew"
import { useCategoryList } from "@/modules/supplier/use/category/useCategoryList"
import { useCertificateNew } from "@/modules/supplier/use/certificate/useCertificateNew"
import ModalAddNewCategory from "@/modules/supplier/components/ModalAddNewCategory"

export default {
  name: "ViewSupplierNew",

  data() {
    return {
      isCreating: false,
      isNameUnique: false,
      isLoadingCategories: false,
      isUploading: false,
      isModalAddNewCategoryOpen: false,

      isFormSubmitting: false,
    }
  },
  setup() {
    const { supplier, supplierValidator$, createSupplier } = useSupplierNew()
    const { categories, getSupplierCategories } = useCategoryList()
    const { certificate, certificateValidator$, uploadCertificate } = useCertificateNew()

    return {
      supplier,
      supplierValidator$,
      createSupplier,

      categories,
      getSupplierCategories,

      certificate,
      certificateValidator$,
      uploadCertificate,
    }
  },
  mounted() {
    this.fetchSupplierCategories()
  },
  methods: {
    // Handle create new supplier
    async handleClickCreateSupplier() {
      this.supplierValidator$.$touch()

      if (this.supplierValidator$.supplier.$invalid) {
        return
      }

      this.isCreating = true

      const supplierRes = await this.createSupplier(this.supplier)
      if (supplierRes && supplierRes.uid) {
        this.supplier = supplierRes

        // trigger pros isFormSubmitting RUploadFile to auto upload file
        this.isFormSubmitting = true

        goto("ViewSupplierList")
      }

      this.isCreating = false
    },

    /** Handle uploaded logo */
    handleCertificateUploaded(result) {
      this.certificate.certs.push(result.url)
    },

    /**
     * create new certificate for supplier
     */
    async createSupplierCertificate() {
      if (this.certificate.certs.length > 0) {
        await this.uploadCertificate(this.supplier.uid, this.certificate)
        goto("ViewSupplierList")
      }
    },

    /**
     * fetch supplier category
     * @returns {Promise<void>}
     */
    async fetchSupplierCategories() {
      this.isLoadingCategories = true
      this.categories = await this.getSupplierCategories()
      this.isLoadingCategories = false
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
  },
  components: { ModalAddNewCategory },
}
</script>
