<template>
  <!-- Modal Delete -->
  <EcModalSimple :isVisible="isModalDeleteResourceOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
    <EcBox class="text-center">
      <EcFlex class="justify-center">
        <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
      </EcFlex>

      <!-- Messages -->
      <EcBox>
        <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
          {{ $t("bcp.labels.confirmToDelete") }}
        </EcHeadline>
        <!-- Org name -->
        <EcText class="text-lg font-bold">
          {{ resourceName }}
        </EcText>
        <EcText class="text-c0-500 mt-4">
          {{ $t("bcp.labels.confirmDeleteQuestion") }}
        </EcText>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
        <EcButton variant="warning" @click="handleDeleteResource">
          {{ $t("bcp.buttons.delete") }}
        </EcButton>
        <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
          {{ $t("bcp.buttons.cancel") }}
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
import { useResourceDetail } from "@/modules/resource/use/resource/useResourceDetail"

export default {
  name: "ModalDeleteResource",

  emits: ["handleCloseDeleteModal", "handleDeleteCallback"],
  data() {
    return {
      isDeleteLoading: false,
    }
  },
  props: {
    isModalDeleteResourceOpen: {
      type: Boolean,
      default: false,
    },

    resourceUid: {
      type: String,
      default: null,
    },

    resourceName: {
      type: String,
      default: "",
    },
  },

  mounted() {},
  setup() {
    const { form, v$, deleteResource } = useResourceDetail()
    return {
      form,
      v$,
      deleteResource,
    }
  },
  methods: {
    /**
     * Cancel add new resource
     */
    async handleDeleteResource() {
      const { uid } = this.$route.params

      if (!uid && !this.resourceUid) {
        goto("ViewResourceList")

        return
      }

      this.isDeleteLoading = true
      const isDeleted = await this.deleteResource(uid ?? this.resourceUid)

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
