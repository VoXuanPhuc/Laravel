<template>
  <!-- Modal Archive -->
  <EcModalSimple :isVisible="isModalArchiveOpen" variant="center-3xl" @overlay-click="handleCloseArchiveModal">
    <EcBox class="text-center">
      <EcFlex class="justify-center">
        <EcIcon class="text-c1-600" width="4rem" height="4rem" icon="Archive" />
      </EcFlex>

      <!-- Messages -->
      <EcBox>
        <EcHeadline as="h2" variant="h2" class="text-c1-600 text-4xl">
          {{ $t("organization.confirmToArchive") }}
        </EcHeadline>
        <!-- Org name -->
        <EcText class="text-lg font-semibold">
          {{ this.organization?.name }}
        </EcText>
        <EcText class="text-c0-500 mt-4">
          {{ $t("organization.confirmArchiveQuestion") }}
        </EcText>
        <EcText class="text-c0-500 mt-2">
          {{ $t("organization.confirmArchiveAction") }}
        </EcText>
      </EcBox>

      <!-- Confirm Org name -->
      <EcBox class="mt-4">
        <RFormInput componentName="EcInputText" v-model="confirmedArchiveOrganizationName"></RFormInput>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
        <EcButton v-if="matchedArchiveOrganizationName" variant="primary" @click="handleArchiveOrganization">
          {{ $t("organization.archive") }}
        </EcButton>
        <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseArchiveModal">
          {{ $t("organization.cancel") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="items-center justify-center mt-10 h-10">
        <EcSpinner type="dots" />
      </EcFlex>
    </EcBox>
  </EcModalSimple>
</template>
<script>
export default {
  name: "ModalArchiveOrganization",

  emits: ["handleCloseArchiveModal"],
  props: {
    organization: {
      type: Object,
      default: () => {},
    },
    isModalArchiveOpen: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      confirmedArchiveOrganizationName: "",
    }
  },
  computed: {
    modalTitle() {
      return this.organization?.is_archived
        ? this.$t("organization.confirmToArchive")
        : this.$t("organization.confirmToUnArchive")
    },

    /**
     * Matched name
     */
    matchedArchiveOrganizationName() {
      return this.confirmedArchiveOrganizationName === this.organization?.name
    },
  },
  methods: {
    handleCloseArchiveModal() {
      this.$emit("handleCloseArchiveModal")
    },
    handleArchiveOrganization() {},
  },
}
</script>
