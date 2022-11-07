<template>
  <!-- context menu -->
  <div>
    <EcBox
      v-if="isOpen"
      class="absolute top-0 z-10 bg-cWhite right-0 shadow-5 rounded w-[100%] md:w-[50%] p-3 text-sm mt"
      :class="menuPosition"
    >
      <EcBox>
        <!-- Name -->
        <EcFlex class="items-center">
          <EcLabel class="text-sm w-3/12">Name</EcLabel>
          <RFormInput
            class="ml-2 w-9/12"
            v-model="bia.name"
            componentName="EcInputText"
            :isSingleSelect="true"
            :allowSelectNothing="false"
          />
        </EcFlex>

        <!-- Due date -->
        <EcFlex class="items-center">
          <EcLabel class="text-sm w-3/12">Due Date</EcLabel>
          <RFormInput
            class="ml-2 w-9/12"
            v-model="bia.due_date"
            componentName="EcDatePicker"
            :isSingleSelect="true"
            :allowSelectNothing="false"
          />
        </EcFlex>

        <!-- Status -->
        <EcFlex class="items-center">
          <EcLabel class="text-sm w-3/12">Status</EcLabel>
          <RFormInput
            v-model="bia.statusObj"
            class="ml-2 w-9/12"
            :options="statuses"
            componentName="EcMultiSelect"
            :isSingleSelect="true"
            :allowSelectNothing="false"
          />
        </EcFlex>

        <!--Errors --->
        <EcBox v-if="v$.bia.$invalid" class="mt-2">
          <EcLabel v-for="error in v$.$errors" :key="error.$propertyPath" class="text-cError-400">
            {{ error?.$property }} {{ error?.$message?.toLowerCase() }}
          </EcLabel>
        </EcBox>

        <!-- Actions -->
        <EcFlex class="mt-2 items-center w-full justify-end">
          <!-- Updaate button -->
          <EcButton v-if="!isSaving" variant="transparent" class="w-fit text-c1-800" @click="handleSaveBIA">
            <EcIcon icon="Save" />
          </EcButton>
          <EcSpinner v-else />
          <!-- Close button -->

          <EcButton variant="transparent" class="w-fit text-c1-800" @click="handleCloseMenu"> Close </EcButton>
        </EcFlex>
      </EcBox>
    </EcBox>
  </div>
</template>
<script>
import { ref } from "vue"
import { useBIADetail } from "@/modules/assessment/use/useBIADetail"

export default {
  name: "BIAContextMenu",

  data() {
    return {
      position: 0,
      isOpen: false,
      isSaving: false,
    }
  },
  props: {
    statuses: {
      type: Array,
      default: () => [],
    },
  },

  setup() {
    const { bia, v$, updateBIA } = useBIADetail()

    return {
      bia,
      v$,
      updateBIA,
    }
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
    async handleSaveBIA() {
      this.v$.$touch()

      if (this.v$.bia.$invalid) {
        return
      }

      this.isSaving = true

      this.bia.status = this.bia.statusObj?.value
      const res = await this.updateBIA(this.bia, this.bia.uid)

      if (res) {
        this.handleCloseMenu()
      }
      this.isSaving = false
    },

    /**
     * Close menu
     */
    handleCloseMenu() {
      this.bia = ref({})
      this.isOpen = false
    },
  },
}
</script>
