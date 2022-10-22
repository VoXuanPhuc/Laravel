<template>
  <EcBox
    :variant="cardVariant"
    class="px-2 py-1 mb-4 mr-3 lg:inline-flex lg:flex-grow lg:w-auto hover:shadow-5"
    style="min-width: 12rem"
  >
    <EcFlex class="relative items-center ml-2 p-1 w-7/12 h-auto overflow-hidden">
      <EcBox>
        <EcText class="font-medium text-md text-cBlack">
          {{ businessUnit.name }}
        </EcText>

        <EcText v-if="businessUnit?.division" class="font-medium text-c0-500 text-xs mt-2">
          {{ businessUnit?.division?.name }}
        </EcText>
      </EcBox>
    </EcFlex>
    <EcBox class="mt-4 lg:mt-0 lg:ml-6">
      <!-- Actions -->
      <EcFlex class="items-center mt-2">
        <!-- Edit -->
        <EcBox v-if="businessUnit.name" class="ml-2">
          <EcButton variant="transparent-rounded" @click="handleClickEdit" title="Edit">
            <EcIcon icon="Pencil" width="20" height="20" class="text-c1-800" />
          </EcButton>
        </EcBox>

        <!-- View -->

        <EcBox v-if="businessUnit.name" class="ml-2">
          <EcButton variant="transparent-rounded" @click="handleOpenModalDeleteBU" title="Delete Business Unit">
            <EcIcon class="text-cError-500" icon="Trash" width="20" height="20" />
          </EcButton>
        </EcBox>

        <!-- End view -->
      </EcFlex>
    </EcBox>
  </EcBox>

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
            {{ $t("organization.confirmToDelete") }}
          </EcHeadline>
          <!-- Org name -->
          <EcText class="text-lg">
            {{ businessUnit?.name }}
          </EcText>
          <EcText class="text-c0-500 mt-4">
            {{ $t("organization.bu.confirmDeleteQuestion") }}
          </EcText>
        </EcBox>

        <!-- Actions -->
        <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
          <EcButton variant="warning" @click="handleClickDeleteBU">
            {{ $t("organization.bu.delete") }}
          </EcButton>
          <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
            {{ $t("organization.bu.cancel") }}
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
import { goto } from "@/modules/core/composables"
import { useBusinessUnitDetail } from "../../use/business_unit/useBusinessUnitDetail"

export default {
  name: "BusinessUnitCardItem",
  props: {
    isActive: {
      type: Boolean,
      default: false,
    },
    organizationUid: {
      type: String,
      default: "",
    },
    organization: {
      type: Object,
      default: () => {},
    },
    businessUnit: {
      type: Object,
      default: () => {},
    },
  },

  emits: ["handleDeletedBuItem"],

  data() {
    return {
      isModalDeleteOpen: false,
      isDeleteLoading: false,
    }
  },

  setup() {
    const { deleteBusinessUnit } = useBusinessUnitDetail()
    return {
      deleteBusinessUnit,
    }
  },
  mounted() {},
  computed: {
    cardVariant() {
      return "card-2"
    },
  },
  methods: {
    statusText(status) {
      return status ? "font-bold text-cSuccess-500" : "font-bold text-cError-500"
    },

    /**
     * Click edit business unit
     */
    handleClickEdit() {
      goto("ViewBusinessUnitDetail", {
        params: {
          organizationUid: this.organizationUid,
          divisionUid: this.businessUnit.division?.uid,
          businessUnitUid: this.businessUnit?.uid,
        },
      })
    },

    /**
     * Delete BU
     */
    async handleClickDeleteBU() {
      this.isDeleteLoading = true
      await this.deleteBusinessUnit(this.businessUnit.uid)
      this.isDeleteLoading = false
      this.$emit("handleDeletedBuItem")
      this.handleCloseDeleteModal()
    },

    /**
     * Handle click delete BU
     */
    handleOpenModalDeleteBU() {
      this.isModalDeleteOpen = true
    },

    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
    },
  },
}
</script>
