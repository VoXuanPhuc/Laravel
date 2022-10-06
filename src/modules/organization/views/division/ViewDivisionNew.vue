<template>
  <RLayout :title="$t('organization.division.new')">
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcBox>
        <!-- Name -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.name"
              componentName="EcInputText"
              :label="$t('organization.name')"
              :validator="v$"
              field="form.name"
              @input="v$.form.name.$touch()"
            />
          </EcBox>
        </EcFlex>

        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.is_active"
              componentName="EcToggle"
              :label="$t('organization.active')"
              showLabel
              :labelTrue="$t('organization.yes')"
              :labelFalse="$t('organization.no')"
            />
          </EcBox>
        </EcFlex>

        <!-- Desc -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.description"
              componentName="EcInputLongText"
              :rows="2"
              :label="$t('organization.division.description')"
              :validator="v$"
              field="form.description"
              @input="v$.form.description.$touch()"
            />
          </EcBox>
        </EcFlex>

        <!-- Color picker -->
        <EcFlex class="flex-wrap max-w-full items-center mb-6">
          <EcBox class="w-full sm:w-2/12 sm:pr-6"> {{ $t("organization.division.icon") }} </EcBox>
          <EcBox class="w-auto sm:w-auto sm:pr-2">
            <EcColorPicker v-model="form.avatar_color" />
          </EcBox>
          <EcBox class="ml-2">
            <img class="w-10 h-auto" :src="avatarUrl" />
          </EcBox>
        </EcFlex>
      </EcBox>
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="primary" @click="handleClickCreate">
          {{ $t("organization.create") }}
        </EcButton>
        <EcButton class="ml-4" variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("organization.cancel") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
  </RLayout>
</template>

<script>
import { useDivisionNew } from "../../use/division/useDivisionNew"
import { goto } from "@/modules/core/composables"
import { ref } from "vue"
import { generateAvatar } from "../../use/division/useDivisionAvatar"

export default {
  name: "ViewOrganizationNew",
  data() {
    return {
      organizationUid: "",
      isLoading: false,
    }
  },

  mounted() {
    const { organizationUid } = this.$route.params
    this.organizationUid = organizationUid
  },
  setup() {
    const { v$, form, createDivision } = useDivisionNew()
    const industries = ref([])

    return {
      v$,
      form,
      createDivision,
      industries,
      generateAvatar,
    }
  },
  computed: {
    avatarUrl() {
      return this.generateAvatar(this.form.name, this.form.avatar_color)
    },
  },
  methods: {
    /**
     * Creaate orgranization
     */
    async handleClickCreate() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      this.isLoading = true
      const response = await this.createDivision(this.form)

      this.isLoading = false

      if (response && response.uid) {
        this.handleCreatedDivision()
      }
    },

    /**
     * Back to organization list
     */
    handleCreatedDivision() {
      goto("ViewDepartmentManagement")
    },
    /**
     * Back to organization list
     */
    handleClickCancel() {
      goto("ViewDepartmentManagement")
    },
  },
}
</script>
