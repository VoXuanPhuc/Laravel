<template>
  <EcModalSimple :isVisible="modalEditRoleOpen" variant="center-md" @overlay-click="$emit('handleClickCancelEditRole')">
    <EcBox class="mb-6">
      <EcText class="font-medium text-c0-300">Edit for</EcText>
      <EcHeadline as="h3" variant="h3" class="mt-2 text-c1-500">
        {{ this.username }}
      </EcHeadline>
    </EcBox>
    <EcFlex class="w-full mb-5">
      <RFormInput
        @change="handleChangeRoleValue"
        componentName="EcSelect"
        label="Role"
        placeholder="Please select"
        :modelValue="currentRole?.uid"
        :options="computedRoleOptions"
      />
    </EcFlex>
    <EcFlex class="mt-10">
      <EcButton v-if="isUserSelectedNewRole" class="mr-4" variant="primary" @click="handleClickBtnUpdateRole">
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
import _ from "lodash"

export default {
  props: {
    username: ref("-"),
    currentRole: ref({}),
    modalEditRoleOpen: Boolean,
    roleOptions: ref([]),
  },

  data() {
    return {
      selectedRoleUid: "",
      isUserSelectedNewRole: false,
    }
  },

  computed: {
    // Transform role options
    computedRoleOptions() {
      return this.roleOptions.map((role) => {
        return {
          name: role.label,
          value: role.uid,
        }
      })
    },
  },

  methods: {
    handleChangeRoleValue(event) {
      this.selectedRoleUid = event.target.value
      this.isUserSelectedNewRole = !_.isEmpty(this.selectedRoleUid) && this.currentRole?.uid !== this.selectedRoleUid
    },

    handleClickBtnUpdateRole() {
      if (_.isEmpty(this.selectedRoleUid)) {
        return
      }

      this.$emit("handleUpdateRole", this.selectedRoleUid)
    },
  },
}
</script>
