<template>
  <!-- context menu -->
  <EcBox
    v-if="isOpen"
    class="absolute top-0 z-10 bg-cWhite right-0 shadow-5 rounded w-[100%] md:w-[50%] p-3 text-sm mt-"
    :class="menuPosition"
  >
    <EcBox>
      <!-- Name -->
      <EcFlex class="items-center">
        <EcLabel class="text-xs w-3/12">Name</EcLabel>
        <RFormInput
          class="w-9/12"
          v-model="bcp.name"
          componentName="EcInputText"
          :isSingleSelect="true"
          :allowSelectNothing="false"
        />
      </EcFlex>

      <!-- Due date -->
      <EcFlex class="items-center">
        <EcLabel class="text-xs w-3/12">Due Date</EcLabel>
        <RFormInput
          class="w-9/12"
          v-model="bcp.due_date"
          :options="statuses"
          componentName="EcDatePicker"
          :isSingleSelect="true"
          :allowSelectNothing="false"
        />
      </EcFlex>

      <!-- Statuses -->
      <EcFlex class="items-center">
        <EcLabel class="text-xs w-3/12">Status</EcLabel>
        <RFormInput
          v-model="bcp.statusObj"
          class="w-9/12"
          :options="statuses"
          componentName="EcMultiSelect"
          :isSingleSelect="true"
          :allowSelectNothing="false"
        />
      </EcFlex>

      <!--Errors --->
      <EcBox v-if="v$.bcp.$invalid" class="mt-2">
        <EcLabel v-for="error in v$.$errors" :key="error.$propertyPath" class="text-cError-400">
          {{ error?.$property }} {{ error?.$message?.toLowerCase() }}
        </EcLabel>
      </EcBox>

      <!-- Actions -->
      <EcFlex class="mt-2 items-center w-full justify-end">
        <!-- Save button -->
        <EcButton v-if="!isSaving" variant="transparent" class="w-fit text-c1-800" @click="handleSaveBCP">
          <EcIcon icon="Save" />
        </EcButton>
        <EcSpinner v-else />
        <!-- Close button -->
        <EcButton variant="transparent" class="w-fit text-c1-800" @click="handleCloseMenu"> Close </EcButton>
      </EcFlex>
    </EcBox>
  </EcBox>
</template>
<script>
import { ref } from "vue"
import { useBCPDetail } from "@/modules/bcp/use/useBCPDetail"

export default {
  name: "BCPContextMenu",

  data() {
    return {
      position: 0,
      isOpen: false,
      isSaving: false,
    }
  },
  setup() {
    const { bcp, v$, updateBCP } = useBCPDetail()

    return {
      bcp,
      v$,
      updateBCP,
    }
  },
  props: {
    statuses: {
      type: Array,
      default: () => [],
    },
  },
  computed: {
    menuPosition() {
      return `mt-${this.position * 4}`
    },
  },
  methods: {
    /**
     *
     */
    async handleSaveBCP() {
      this.v$.$touch()

      if (this.v$.bcp.$invalid) {
        return
      }

      this.isSaving = true

      this.bcp.status = this.bcp.statusObj?.value
      const res = await this.updateBCP(this.bcp, this.bcp.uid)

      if (res) {
        this.handleCloseMenu()
      }
      this.isSaving = false
    },

    /**
     * Close menu
     */
    handleCloseMenu() {
      this.bcp = ref({})
      this.isOpen = false
    },
  },
}
</script>
