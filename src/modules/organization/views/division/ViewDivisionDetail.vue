<template>
  <RLayout :title="$t('organization.division.edit')">
    <RLayoutTwoCol v-if="!isLoading">
      <template #left>
        <EcBox variant="card-1" class="width-full px-4 sm:px-10">
          <EcBox>
            <!-- Name -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full mb-6 sm:pr-6">
                <RFormInput
                  v-model="division.name"
                  componentName="EcInputText"
                  :label="$t('organization.division.name')"
                  :validator="v$"
                  field="division.name"
                  @input="v$.division.name.$touch()"
                />
              </EcBox>
            </EcFlex>

            <!-- Status -->
            <EcFlex class="flex-wrap max-w-md">
              <EcBox class="w-full mb-6 sm:pr-6">
                <RFormInput
                  v-model="division.is_active"
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
              <EcBox class="w-full mb-6 sm:pr-6">
                <RFormInput
                  v-model="division.description"
                  componentName="EcInputLongText"
                  :rows="2"
                  :label="$t('organization.division.description')"
                  :validator="v$"
                  field="division.description"
                  @input="v$.division.description.$touch()"
                />
              </EcBox>
            </EcFlex>

            <!-- Color picker -->
            <EcFlex class="flex-wrap max-w-full items-center mb-6">
              <EcLabel class="w-full sm:w-3/12 sm:pr-6 text-base font-semibold">
                {{ $t("organization.division.icon") }}
              </EcLabel>
              <EcBox class="w-auto sm:w-auto sm:pr-2">
                <EcColorPicker v-model="division.avatar_color" />
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
          <EcFlex v-if="!isUpdateLoading" class="mt-6">
            <EcButton variant="primary" @click="handleClickUpdate">
              {{ $t("organization.division.update") }}
            </EcButton>
            <EcButton class="ml-4" variant="tertiary-outline" @click="handleClickCancel">
              {{ $t("organization.division.back") }}
            </EcButton>
          </EcFlex>

          <!-- Loading -->
          <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
        </EcBox>
      </template>

      <!-- Delete -->
      <template #right>
        <!-- Delete organization -->
        <EcBox variant="card-1" class="mb-8">
          <EcHeadline as="h2" variant="h2" class="text-c1-800 px-5">
            {{ $t("organization.division.deleteHeader") }}
          </EcHeadline>
          <EcText class="px-5 my-6 text-c0-500 leading-normal">
            {{ $t("organization.division.deleteNote") }}
          </EcText>
          <EcButton class="mx-5" variant="warning" iconPrefix="Trash" @click="handleOpenDeleteModal">
            {{ $t("organization.division.deleteButton") }}
          </EcButton>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <RLoading v-else />

    <!-- Modals -->
    <teleport to="#layer2">
      <!-- Modal Delete -->
      <EcModalSimple :isVisible="isModalDeleteOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
        <EcBox class="text-center">
          <EcFlex class="justify-center">
            <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
          </EcFlex>

          <!-- Messages -->
          <EcBox>
            <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
              {{ $t("organization.confirmToDelete") }}
            </EcHeadline>
            <!-- Org name -->
            <EcText class="text-lg">
              {{ this.division.name }}
            </EcText>
            <EcText class="text-c0-500 mt-4">
              {{ $t("organization.division.confirmDeleteQuestion") }}
            </EcText>
          </EcBox>

          <!-- Actions -->
          <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
            <EcButton variant="warning" @click="handleDeleteDivision">
              {{ $t("organization.division.delete") }}
            </EcButton>
            <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
              {{ $t("organization.division.cancel") }}
            </EcButton>
          </EcFlex>
          <EcFlex v-else class="items-center justify-center mt-10 h-10">
            <EcSpinner type="dots" />
          </EcFlex>
        </EcBox>
      </EcModalSimple>
    </teleport>
  </RLayout>
</template>

<script>
import { useDivisionDetail } from "../../use/division/useDivisionDetail"
import { generateAvatar } from "../../use/division/useDivisionAvatar"
import { goto } from "@/modules/core/composables"
import { ref } from "vue"
import RLoading from "@/modules/core/components/common/RLoading.vue"
import RFormInput from "@/modules/core/components/common/RFormInput.vue"
import RLayoutTwoCol from "@/modules/core/components/common/RLayoutTwoCol.vue"

export default {
  name: "ViewOrganizationNew",
  data() {
    return {
      isModalDeleteOpen: false,
      organizationUid: "",
      isLoading: false,
      isUpdateLoading: false,
      isDeleteLoading: false,
      logoTitle: "Logo",
    }
  },
  mounted() {
    const { organizationUid } = this.$route.params
    this.organizationUid = organizationUid
    this.fetchDivision()
  },
  setup() {
    const { v$, division, getDivision, updateDivision, deleteDivision } = useDivisionDetail()
    const industries = ref([])
    return {
      v$,
      division,
      getDivision,
      updateDivision,
      deleteDivision,
      generateAvatar,
      industries,
    }
  },
  computed: {
    avatarUrl() {
      return this.generateAvatar(this.division.name, this.division.avatar_color)
    },
  },
  methods: {
    /**
     * Fetch division with uid
     */
    async fetchDivision() {
      const { divisionUid } = this.$route.params

      this.isLoading = true
      const response = await this.getDivision(divisionUid)
      this.isLoading = false

      if (response && response.uid) {
        this.division = response
      }
    },
    /**
     * Update division
     */
    async handleClickUpdate() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }
      this.isUpdateLoading = true
      const response = await this.updateDivision(this.division, this.division.uid)
      this.isUpdateLoading = false

      if (response && response.uid) {
        this.division = response
      }
    },

    /**
     * Delete division
     */
    async handleDeleteDivision() {
      this.isDeleteLoading = true

      await this.deleteDivision(this.division.uid)

      setTimeout(this.gotoDepartmentManagement(), 500)
      this.isDeleteLoading = false
    },

    /**
     * Back to organization list
     */
    handleClickCancel() {
      this.gotoDepartmentManagement()
    },

    /** Open delete modal */
    handleOpenDeleteModal() {
      this.isModalDeleteOpen = true
    },

    /** Close delete modal */
    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
    },

    /**
     * Go to Department Management
     */
    gotoDepartmentManagement() {
      goto("ViewDepartmentManagement")
    },
  },
  components: { RLoading, RFormInput, RLayoutTwoCol },
}
</script>
