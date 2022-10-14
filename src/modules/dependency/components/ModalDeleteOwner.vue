<template>
  <!-- Modal Delete -->
  <EcModalSimple :isVisible="isModalDeleteOwnerOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
    <EcBox class="text-center">
      <EcFlex class="justify-center">
        <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
      </EcFlex>

      <!-- Messages -->
      <EcBox>
        <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
          {{ $t("resource.labels.confirmToDelete") }}
        </EcHeadline>
        <!-- Owners name -->
        <EcText class="text-lg font-bold">
          {{ ownerName }}
        </EcText>
        <EcText class="text-c0-500 mt-4">
          {{ $t("resource.owner.labels.confirmDeleteQuestion") }}
        </EcText>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
        <EcButton variant="warning" @click="handleDeleteOwner">
          {{ $t("resource.owner.buttons.delete") }}
        </EcButton>
        <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
          {{ $t("resource.owner.buttons.cancel") }}
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
import { useOwnerDetail } from "../use/owner/useOwnerDetail"

export default {
  name: "ModalDeleteOwner",

  emits: ["handleCloseDeleteModal", "handleDeleteCallback"],
  data() {
    return {
      isDeleteLoading: false,
    }
  },
  props: {
    isModalDeleteOwnerOpen: {
      type: Boolean,
      default: false,
    },

    ownerUid: {
      type: String,
      default: null,
    },

    ownerName: {
      type: String,
      default: "",
    },
  },

  mounted() {},
  setup() {
    const { form, v$, deleteResourceOwner } = useOwnerDetail()
    return {
      form,
      v$,
      deleteResourceOwner,
    }
  },
  methods: {
    /**
     * Cancel add new activity
     */
    async handleDeleteOwner() {
      const { uid } = this.$route.params

      if (!uid && !this.ownerUid) {
        goto("ViewOwnerList")

        return
      }

      this.isDeleteLoading = true
      const response = await this.deleteResourceOwner(uid ?? this.ownerUid)

      if (response) {
        this.handleCloseDeleteModal()
        this.handleDeleteCallback()
      }
      this.isDeleteLoading = false
    },

    /**
     * Call back after delete
     */
    handleDeleteCallback() {
      this.$emit("handleDeleteCallback")
    },

    /**
     * Close cancel modal
     */
    handleCloseDeleteModal() {
      this.$emit("handleCloseDeleteModal")
    },
  },
}
</script>
