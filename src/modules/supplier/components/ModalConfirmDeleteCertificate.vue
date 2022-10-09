<template>
  <!-- Modals -->
  <EcModalSimple :isVisible="isModalDeleteOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
    <EcBox class="text-center">
      <EcFlex class="justify-center">
        <EcIcon class="text-cError-500" height="4rem" icon="TrashAlt" width="4rem" />
      </EcFlex>

      <!-- Messages -->
      <EcBox>
        <EcHeadline as="h2" class="text-cError-500 text-4xl" variant="h2">
          {{ $t("supplier.certificate.confirmToDelete") }}
        </EcHeadline>
        <!-- Category name -->
        <EcText class="text-lg">
          <a :href="certificate.cert_path" target="_blank"> {{ originalFileName }} </a>
        </EcText>
        <EcText class="text-c0-500 mt-4">
          {{ $t("supplier.certificate.confirmDeleteQuestion") }}
        </EcText>
        <EcText class="text-c0-500 mt-2">
          {{ $t("supplier.certificate.confirmDeleteAction") }}
        </EcText>
      </EcBox>

      <!-- Confirm certificate file name -->
      <EcBox class="mt-4">
        <RFormInput v-model="confirmedOriginalFileName" componentName="EcInputText"></RFormInput>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
        <EcButton v-if="matchedName" variant="warning" @click="handleDeletedModal">
          {{ $t("supplier.buttons.delete") }}
        </EcButton>
        <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
          {{ $t("supplier.buttons.cancel") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="items-center justify-center mt-10 h-10">
        <EcSpinner type="dots" />
      </EcFlex>
    </EcBox>
  </EcModalSimple>
</template>

<script>
import { useCertificateDelete } from "@/modules/supplier/use/certificate/useCertificateDelete"
export default {
  name: "ModalConfirmDeleteCertificate",
  setup() {
    const { deleteCertificate } = useCertificateDelete()
    return {
      deleteCertificate,
    }
  },
  data() {
    return {
      isDeleteLoading: false,
      originalFileName: "",
      confirmedOriginalFileName: "",
    }
  },

  emits: ["handleCallBackDeletedModal", "handleCloseDeleteModal"],

  props: {
    isModalDeleteOpen: {
      type: Boolean,
      default: false,
    },
    certificate: {
      require,
      type: Object,
    },
    supplierUid: {
      require,
      type: String,
    },
  },

  watch: {
    certificate() {
      this.confirmedOriginalFileName = ""
      this.originalFileName = this.getOriginalFileName(this.certificate.cert_path)
    },
  },

  methods: {
    /**
     * close delete modal
     */
    handleCloseDeleteModal() {
      this.$emit("handleCloseDeleteModal")
    },

    /**
     * call back after delete supplier
     */
    handleCallBackDeletedModal() {
      this.$emit("handleCallBackDeletedModal")
    },

    /**
     * handle Delete modal
     */
    async handleDeletedModal() {
      this.isDeleteLoading = true
      const deleteCertificateRes = await this.deleteCertificate(this.supplierUid, this.certificate.uid)
      if (deleteCertificateRes) {
        this.handleCallBackDeletedModal()
      }
      this.isDeleteLoading = false
    },

    /**
     * get original fileName
     * @param url
     * @returns {string}
     */
    getOriginalFileName(url) {
      let fileName = ""
      if (url) {
        for (let i = url.length - 1; i >= 0; i--) {
          if (url[i] === "/") break
          fileName = url[i] + fileName
        }
      }
      return fileName
    },
  },

  computed: {
    matchedName() {
      return this.confirmedOriginalFileName === this.originalFileName
    },
  },
}
</script>
