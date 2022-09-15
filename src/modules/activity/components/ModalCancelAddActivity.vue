<template>
  <EcModalSimple :isVisible="isModalCancelOpen" variant="center-3xl" @overlay-click="handleCloseCancelModal">
    <EcBox class="text-center">
      <EcFlex class="justify-center">
        <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
      </EcFlex>

      <!-- Messages -->
      <EcBox>
        <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
          {{ $t("activity.title.confirmToCancel") }}
        </EcHeadline>

        <EcText class="text-c0-500 mt-4">
          {{ $t("activity.title.cancelQuestion") }}
        </EcText>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isCancelLoading" class="justify-center mt-10">
        <EcButton variant="warning" @click="handleCancelAddNewActivity">
          {{ $t("activity.buttons.cancel") }}
        </EcButton>
        <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseCancelModal">
          {{ $t("activity.buttons.close") }}
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

export default {
  name: "ModalCancelAddActivity",

  emits: ["handleCloseCancelModal"],
  data() {
    return {
      isCancelLoading: false,
    }
  },
  props: {
    isModalCancelOpen: {
      type: Boolean,
      default: false,
    },
  },

  mounted() {
    window.addEventListener("beforeunload", this.leaving)
  },
  methods: {
    /**
     * Cancel add new activity
     */
    handleCancelAddNewActivity() {
      goto("ViewActivityList")
    },

    /**
     * Close cancel modal
     */
    handleCloseCancelModal() {
      this.$emit("handleCloseCancelModal")
    },

    leaving(e) {
      alert(e)
    },
  },
}
</script>
