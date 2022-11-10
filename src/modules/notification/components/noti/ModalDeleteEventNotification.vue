<template>
  <!-- Modal Delete -->
  <EcModalSimple :isVisible="isModalDeleteEventNotificationOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
    <EcBox class="text-center">
      <EcFlex class="justify-center">
        <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
      </EcFlex>

      <!-- Messages -->
      <EcBox>
        <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
          {{ $t("notification.labels.confirmToDelete") }}
        </EcHeadline>
        <!-- Event Noti name -->
        <EcText class="text-lg font-bold">
          {{ eventNotification?.name }}
        </EcText>
        <EcText class="text-c0-500 mt-4">
          {{ $t("notification.labels.confirmDeleteQuestion") }}
        </EcText>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
        <EcButton variant="warning" @click="handleDeleteEventNotification">
          {{ $t("notification.buttons.delete") }}
        </EcButton>
        <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
          {{ $t("notification.buttons.cancel") }}
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
import { useEventNotificationDetail } from "../../use/noti/useEventNotificationDetail"

export default {
  name: "ModalDeleteEventNotification",

  emits: ["handleCloseDeleteModal", "handleDeleteCallback"],
  data() {
    return {
      isDeleteLoading: false,
    }
  },
  props: {
    isModalDeleteEventNotificationOpen: {
      type: Boolean,
      default: false,
    },

    eventNotification: {
      type: Object,
      default: () => {},
    },
  },

  mounted() {},
  setup() {
    const { form, v$, deleteEventNotification } = useEventNotificationDetail()
    return {
      form,
      v$,
      deleteEventNotification,
    }
  },
  methods: {
    /**
     * Delete EventNotification
     */
    async handleDeleteEventNotification() {
      if (!this.eventNotification?.uid) {
        goto("ViewBCPList")

        return
      }

      this.isDeleteLoading = true
      const isDeleted = await this.deleteEventNotification(this.eventNotification?.uid)

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
