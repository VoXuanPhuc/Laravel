<template>
  <EcModalSimple :isVisible="modalEditRoleOpen" variant="center-md" @overlay-click="$emit('handleClickCancelEditRole')">
    <EcBox class="mb-6">
      <EcText class="font-medium text-c0-300">Edit for</EcText>
      <EcHeadline as="h3" variant="h3" class="mt-2 text-c1-500">
        {{ $t("user.role") }}
      </EcHeadline>
    </EcBox>
    <EcFlex class="w-full mb-5">
      <RFormInput
        @change="handleChangeRoleValue"
        componentName="EcSelect"
        label="Role"
        placeholder="Please select"
        :options="roleOptions"
      />
    </EcFlex>
    <EcFlex class="mt-10">
      <EcButton v-if="isChooseNewRole" class="mr-4" variant="primary" @click="$emit('handleUpdateRole')">
        {{ $t("user.button.confirm") }}
      </EcButton>
      <EcButton variant="tertiary-outline" @click="$emit('handleClickCancelEditRole')">
        {{ $t("user.button.cancel") }}
      </EcButton>
    </EcFlex>
  </EcModalSimple>
</template>

<script>
import { ref } from "vue"

export default {
  props: {
    newRole: ref(""),
    modalEditRoleOpen: Boolean,
    isChooseNewRole: Boolean,
    roleOptions: ref([]),
  },

  methods: {
    handleChangeRoleValue(event) {
      this.$emit("handleChangeNewRoleInModal", event.target.value)
    },
  },
}
</script>
