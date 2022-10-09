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
          {{ $t("supplier.confirmToDelete") }}
        </EcHeadline>
        <!-- Category name -->
        <EcText class="text-lg">
          {{ supplier.name }}
        </EcText>
        <EcText class="text-c0-500 mt-4">
          {{ $t("supplier.labels.confirmDeleteQuestion") }}
        </EcText>
        <EcText class="text-c0-500 mt-2">
          {{ $t("supplier.labels.confirmDeleteAction") }}
        </EcText>
      </EcBox>

      <!-- Confirm supplier name -->
      <EcBox class="mt-4">
        <RFormInput v-model="confirmedSupplierName" componentName="EcInputText"></RFormInput>
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
import { useSupplierDelete } from "@/modules/supplier/use/supplier/useSupplierDelete"
export default {
  name: "ModalConfirmDeleteSupplier",
  setup() {
    const { deleteSupplier } = useSupplierDelete()

    return {
      deleteSupplier,
    }
  },
  data() {
    return {
      isDeleteLoading: false,
      confirmedSupplierName: "",
      supplierName: "",
    }
  },

  emits: ["handleCallBackDeletedModal", "handleCloseDeleteModal"],

  props: {
    isModalDeleteOpen: {
      type: Boolean,
      default: false,
    },
    supplier: {
      require,
      type: Object,
    },
  },
  watch: {
    supplier() {
      this.confirmedSupplierName = ""
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
      const response = await this.deleteSupplier(this.supplier.uid)
      if (response) {
        this.handleCallBackDeletedModal()
      }
      this.isDeleteLoading = false
    },
  },

  computed: {
    matchedName() {
      return this.confirmedSupplierName === this.supplier.name
    },
  },
}
</script>
