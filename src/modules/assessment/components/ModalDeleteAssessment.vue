<template>
  <!-- Modal Delete -->
  <EcModalSimple :isVisible="isModalDeleteBIAOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
    <EcBox class="text-center">
      <EcFlex class="justify-center">
        <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
      </EcFlex>

      <!-- Messages -->
      <EcBox>
        <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
          {{ $t("bia.labels.confirmToDelete") }}
        </EcHeadline>
        <!-- BIA name -->
        <EcText class="text-lg font-bold">
          {{ biaName }}
        </EcText>
        <EcText class="text-c0-500 mt-4">
          {{ $t("bia.labels.confirmDeleteQuestion") }}
        </EcText>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
        <EcButton variant="warning" @click="handleDeleteBIA">
          {{ $t("bia.buttons.delete") }}
        </EcButton>
        <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
          {{ $t("bia.buttons.cancel") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="items-center justify-center mt-10 h-10">
        <EcSpinner type="dots" />
      </EcFlex>
    </EcBox>
  </EcModalSimple>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useBIADetail } from "@/modules/assessment/use/useBIADetail"

export default {
  name: "ModalDeleteBIA",

  emits: ["handleCloseDeleteModal", "handleDeleteCallback"],
  data() {
    return {
      isDeleteLoading: false,
    }
  },
  props: {
    isModalDeleteBIAOpen: {
      type: Boolean,
      default: false,
    },

    biaUid: {
      type: String,
      default: null,
    },

    biaName: {
      type: String,
      default: "",
    },
  },

  mounted() {},
  setup() {
    const { deleteBIA } = useBIADetail()
    return {
      deleteBIA,
    }
  },
  methods: {
    /**
     * Delete BCP
     */
    async handleDeleteBIA() {
      const { uid } = this.$route.params

      if (!uid && !this.biaUid) {
        goto("ViewBIAList")

        return
      }

      this.isDeleteLoading = true
      const isDeleted = await this.deleteBIA(uid ?? this.biaUid)

      if (isDeleted) {
        this.handleCloseDeleteModal()
        this.handleDeleteCallback()
      }
      this.isDeleteLoading = false
    },

    /**
     * Close cancel modal
     */
    handleCloseDeleteModal() {
      this.$emit("handleCloseDeleteModal")
    },

    /**
     * Delete callback
     */
    handleDeleteCallback() {
      this.$emit("handleDeleteCallback")
    },
  },
}
</script>
