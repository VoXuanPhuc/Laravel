<template>
  <EcModalSimple :isVisible="isModalAddNewOwnerOpen" variant="center-3xl">
    <EcBox>
      <!-- Messages -->
      <EcBox class="text-center">
        <EcHeadline as="h2" variant="h2" class="text-4xl">
          {{ $t("resource.owner.labels.createOwner") }}
        </EcHeadline>
      </EcBox>

      <!-- First name -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="form.first_name"
          :label="$t('resource.owner.labels.firstName')"
          componentName="EcInputText"
          :validator="v$"
          field="form.first_name"
          @input="v$.form.first_name.$touch()"
        ></RFormInput>
      </EcBox>

      <!-- Last name -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="form.last_name"
          :label="$t('resource.owner.labels.lastName')"
          componentName="EcInputText"
          :validator="v$"
          field="form.last_name"
          @input="v$.form.last_name.$touch()"
        ></RFormInput>
      </EcBox>

      <!-- Email -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="form.email"
          :label="$t('resource.owner.labels.email')"
          componentName="EcInputText"
          :validator="v$"
          field="form.email"
          @input="v$.form.email.$touch()"
        ></RFormInput>
      </EcBox>

      <!-- Invite to RBC user -->
      <EcBox class="mt-4">
        <RFormInput
          :label="$t('resource.owner.labels.inviteToUser')"
          v-model="form.is_invite"
          componentName="EcToggle"
          showLabel
          :labelTrue="$t('organization.yes')"
          :labelFalse="$t('organization.no')"
        ></RFormInput>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isLoading" class="justify-end mt-10">
        <EcButton variant="tertiary-outline" @click="handleCloseAddNewOwnerModal">
          {{ $t("resource.modal.buttons.cancel") }}
        </EcButton>

        <EcButton class="ml-3" variant="primary" @click="handleClickCreateOwner">
          {{ $t("resource.modal.buttons.create") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="items-center justify-center mt-10 h-10">
        <EcSpinner type="dots" />
      </EcFlex>
    </EcBox>
  </EcModalSimple>
</template>
<script>
import { useOwnerNew } from "../use/owner/useOwnerNew"

export default {
  name: "ModalAddNewOwner",

  emits: ["handleCloseAddNewOwnerModal", "handleCallbackAddNewOwner"],
  data() {
    return {
      isLoading: false,
    }
  },
  props: {
    isModalAddNewOwnerOpen: {
      type: Boolean,
      default: false,
    },
  },

  setup() {
    const { form, v$, createOwner } = useOwnerNew()
    return {
      form,
      v$,
      createOwner,
    }
  },
  methods: {
    /**
     * Cancel add new owner
     */
    async handleClickCreateOwner() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      this.isLoading = true
      const response = await this.createOwner(this.form)

      if (response) {
        this.handleCloseAddNewOwnerModal()
        this.handleCallbackAddNewOwner()
      }
      this.isLoading = false
    },

    /**
     * Close cancel modal
     */
    handleCloseAddNewOwnerModal() {
      this.$emit("handleCloseAddNewOwnerModal")
    },

    handleCallbackAddNewOwner() {
      this.$emit("handleCallbackAddNewOwner")
    },
  },

  watch: {
    isModalAddNewOwnerOpen() {
      this.form.first_name = null
      this.form.last_name = null
      this.form.email = null

      this.v$.form.$reset()
    },
  },
}
</script>
