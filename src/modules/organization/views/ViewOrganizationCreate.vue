<template>
  <RLayout :title="$t('organization.newOrganization')">
    <EcBox variant="card-1" class="max-w-2xl mt-8 px-4 sm:px-10">
      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="nameFormat"
            componentName="EcInputText"
            :label="$t('organization.name')"
            :validator="v"
            field="nameFormat"
            @input="v.nameFormat.$touch()"
          />
        </EcBox>
      </EcFlex>
      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="isActive"
            componentName="EcToggle"
            :label="$t('organization.active')"
            showLabel
            :labelTrue="$t('organization.yes')"
            :labelFalse="$t('organization.no')"
          />
        </EcBox>
      </EcFlex>
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="primary" @click="handleClickCreate">
          {{ $t("organization.create") }}
        </EcButton>
        <EcButton class="ml-4" variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("organization.cancel") }}
        </EcButton>
      </EcFlex>
      <EcBox v-else class="flex items-center mt-6 h-10">
        <EcSpinner variant="secondary" type="dots" />
      </EcBox>
    </EcBox>
  </RLayout>
</template>

<script>
import { useOrganizationCreate } from "./../use/useOrganizationCreate"

export default {
  name: "ViewParticipantNew",
  setup() {
    const { state, send, nameFormat, isActive, v, message } = useOrganizationCreate()

    return {
      state,
      send,
      nameFormat,
      isActive,
      v,
      message,
    }
  },
  computed: {
    isLoading() {
      return false
    },
  },
  methods: {
    handleClickCreate() {
      this.v.$touch()
      if (this.v.$invalid) return
      console.log("create")
    },
    handleClickCancel() {
      // Go to Participant List page
    },
  },
}
</script>
