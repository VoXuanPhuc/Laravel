<template>
  <RLayout :title="organization?.name">
    <RLayoutTwoCol :isLoading="isLoading">
      <template #left>
        <EcBox variant="card-1" class="width-full px-4 sm:px-10">
          <!-- Organization detail -->
          <EcText class="font-bold text-lg">Organization Detail</EcText>
          <EcBox>
            <!-- Logo -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full mb-6 sm:pr-6">
                <RUploadFiles
                  :documentTitle="logoTitle"
                  :maxFileNum="1"
                  :isImage="true"
                  :isUploadOnSelect="true"
                  :uploadedFileUrls="uploadedFileUrls"
                  dropZoneCls="border-c0-500 border-dashed border-2 bg-cWhite p-2 md:py-4"
                  @handleSignleFileUploaded="handleLogoUploaded"
                />
              </EcBox>
            </EcFlex>

            <!-- Code -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-7/12 mb-6 sm:pr-6">
                <RFormInput
                  variant="primary-disabled"
                  :disabled="true"
                  v-model="organization.code"
                  componentName="EcInputText"
                  :label="$t('organization.code')"
                  :validator="v$"
                  field="organization.code"
                  @input="v$.organization.code.$touch()"
                />
              </EcBox>
            </EcFlex>

            <!-- Name -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.name"
                  componentName="EcInputText"
                  :label="$t('organization.name')"
                  :validator="v$"
                  field="organization.name"
                  @input="v$.organization.name.$touch()"
                />
              </EcBox>
            </EcFlex>

            <!-- Status -->
            <EcFlex v-if="!isLandlord" class="flex-wrap max-w-md">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  componentName="EcToggle"
                  v-model="organization.is_active"
                  :label="$t('organization.active')"
                  showLabel
                  :labelTrue="$t('organization.yes')"
                  :labelFalse="$t('organization.no')"
                />
              </EcBox>
            </EcFlex>

            <!-- Desc -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.description"
                  componentName="EcInputLongText"
                  :rows="2"
                  :label="$t('organization.desc')"
                  :validator="v$"
                  field="form.description"
                  @input="v$.organization.description.$touch()"
                />
              </EcBox>
            </EcFlex>

            <!-- Friendly URL -->
            <EcFlex v-if="!isLandlord" class="flex-wrap items-center max-w-full">
              <EcBox class="w-4/12 mb-6">
                <RFormInput
                  v-model="organization.friendly_url"
                  componentName="EcInputText"
                  :label="$t('organization.friendlyUrl')"
                  :validator="v$"
                  field="organization.address"
                  placeholder="will be generated if empty"
                  @input="v$.organization.friendly_url.$touch()"
                />
              </EcBox>
              <EcFlex class="items-center">
                <EcText>.{{ hostName }}</EcText>
                <EcSpinner v-if="isCheckingFriendlyUrl" class="ml-4" variant="basic" />
              </EcFlex>

              <!-- Open link -->
              <EcButton variant="transparent" class="h-3" :href="'https://' + organization?.domain" target="_blank">
                <EcIcon icon="OpenUp" width="20" height="20" />
              </EcButton>
            </EcFlex>

            <!-- Industries -->
            <EcFlex v-if="!isLandlord" class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <EcText class="mb-2">Industries</EcText>
                <EcMultiSelect :modelValue="organization.industries" :options="industries" :valueKey="'uid'" />
              </EcBox>
            </EcFlex>

            <!-- Address -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.address"
                  componentName="EcInputText"
                  :label="$t('organization.address')"
                  :validator="v$"
                  field="form.address"
                  @input="v$.form.address.$touch()"
                />
              </EcBox>
            </EcFlex>
          </EcBox>
        </EcBox>

        <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
          <EcText class="mb-4 font-bold text-lg">Organization Owner</EcText>

          <!-- Owner first name -->
          <EcBox>
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.owner.first_name"
                  componentName="EcInputText"
                  :rows="2"
                  :label="$t('organization.owner.firstName')"
                  :validator="v$"
                  field="organization.owner.first_name"
                  @input="v$.organization.owner.first_name.$touch()"
                />
              </EcBox>
            </EcFlex>
          </EcBox>

          <!-- Owner last name -->
          <EcBox>
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.owner.last_name"
                  componentName="EcInputText"
                  :rows="2"
                  :label="$t('organization.owner.lastName')"
                  :validator="v$"
                  field="organization.owner.last_name"
                  @input="v$.organization.owner.last_name.$touch()"
                />
              </EcBox>
            </EcFlex>
          </EcBox>

          <!-- Owner email -->
          <EcBox>
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.owner.email"
                  componentName="EcInputText"
                  :rows="2"
                  :label="$t('organization.owner.email')"
                  :validator="v$"
                  field="organization.owner.email"
                  @input="v$.organization.owner.email.$touch()"
                />
              </EcBox>
            </EcFlex>
          </EcBox>

          <!-- Owner phone -->
          <EcBox>
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.owner.phone"
                  componentName="EcInputText"
                  :rows="2"
                  :label="$t('organization.owner.phone')"
                  :validator="v$"
                  field="form.owner.phone"
                  @input="v$.organization.owner.phone.$touch()"
                />
              </EcBox>
            </EcFlex>
          </EcBox>
          <!-- end -->
        </EcBox>

        <!-- Actions -->
        <EcBox class="width-full mt-8 px-4 sm:px-10">
          <!-- Button create -->
          <EcFlex v-if="!isUpdateLoading" class="mt-6">
            <EcButton variant="primary" @click="handleClickUpdateOrganization">
              {{ $t("organization.update") }}
            </EcButton>
            <EcButton class="ml-4" variant="tertiary-outline" @click="handleCancelUpdateOrganization">
              {{ $t("organization.back") }}
            </EcButton>
          </EcFlex>

          <!-- Loading -->
          <EcBox v-else class="flex items-center mt-6 h-10">
            <EcSpinner variant="secondary" type="dots" />
          </EcBox>
        </EcBox>
      </template>
      <template #right>
        <!-- Delete organization -->
        <EcBox v-if="!isLandlord" variant="card-1" class="mb-8">
          <EcHeadline as="h2" variant="h2" class="text-c1-800 px-5">
            {{ $t("organization.deleteOrganization") }}
          </EcHeadline>
          <EcText class="px-5 my-6 text-c0-500 leading-normal">
            {{ $t("organization.noteDeleteOrganization") }}
          </EcText>
          <EcButton class="mx-5" variant="warning" iconPrefix="Trash" @click="handleOpenDeleteModal">
            {{ $t("organization.deleteOrganization") }}
          </EcButton>
        </EcBox>
      </template>
    </RLayoutTwoCol>

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
              {{ this.organization.name }}
            </EcText>
            <EcText class="text-c0-500 mt-4">
              {{ $t("organization.confirmDeleteQuestion") }}
            </EcText>
            <EcText class="text-c0-500 mt-2">
              {{ $t("organization.confirmDeletAction") }}
            </EcText>
          </EcBox>

          <!-- Confirm Org name -->
          <EcBox class="mt-4">
            <RFormInput componentName="EcInputText" v-model="confirmedOrganizationName"></RFormInput>
          </EcBox>

          <!-- Actions -->
          <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
            <EcButton v-if="matchedName" variant="warning" @click="handleDeleteOrganization">
              {{ $t("organization.delete") }}
            </EcButton>
            <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
              {{ $t("organization.cancel") }}
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
import { useOrganizationDetail } from "./../../use/organization/useOrganizationDetail"
import { goto } from "@/modules/core/composables"
import { useIndustry } from "../../use/industry/useIndustry"
import { ref } from "vue"

export default {
  name: "ViewOrganizationDetail",
  data() {
    return {
      isLoading: false,
      isUpdateLoading: false,
      isDeleteLoading: false,
      isCheckingFriendlyUrl: false,
      logoTitle: "Logo",
      isModalDeleteOpen: false,
      confirmedOrganizationName: "",
      fileOf: "organization",
      uploadedFileUrls: [],
    }
  },
  setup() {
    const { organization, v$, getOrganization, updateOrganization, deleteOrganization } = useOrganizationDetail()
    const { getIndustries, getTransformedIndustries } = useIndustry()

    const industries = ref([])

    return {
      industries,
      getIndustries,
      organization,
      v$,
      getOrganization,
      updateOrganization,
      deleteOrganization,
      getTransformedIndustries,
    }
  },
  computed: {
    matchedName() {
      return this.confirmedOrganizationName === this.organization.name
    },

    isLandlord() {
      return this.organization?.landlord === true
    },

    hostName() {
      return process.env.VUE_APP_HOST_NAME
    },
  },

  mounted() {
    this.fetchIndustries()
    this.fetchOrganization()
  },

  methods: {
    /**
     * Fetch Org
     */
    async fetchOrganization() {
      this.isLoading = true
      const { uid } = this.$route.params
      const orgRes = await this.getOrganization(uid)

      if (orgRes) {
        this.organization = orgRes
      }

      if (this.organization) {
        this.uploadedFileUrls = [this.organization.logo_path]
      }
      this.isLoading = false
    },

    /**
     * Fetch industries
     */
    async fetchIndustries() {
      this.industries = await this.getIndustries()
    },

    /**
     * Handle click update organization
     */
    async handleClickUpdateOrganization() {
      this.isUpdateLoading = true

      const org = await this.updateOrganization(this.organization.uid, this.organization)

      if (org && org.uid) {
        this.organization = org

        if (!this.organization.owner) {
          this.organization.owner = {}
        }
      }
      this.isUpdateLoading = false
    },

    /** Handle delete organization */
    handleDeleteOrganization() {
      this.isDeleteLoading = true
      this.deleteOrganization(this.organization.uid)

      // Redirect to organization list
      setTimeout(() => {
        goto("ViewOrganizationList")
      }, 1000)
    },

    /** Cancel update */
    handleCancelUpdateOrganization() {
      goto("ViewOrganizationList")
    },

    /** Open delete modal */
    handleOpenDeleteModal() {
      this.isModalDeleteOpen = true
    },

    /** Close delete modal */
    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
      this.confirmedOrganizationName = ""
    },

    /**
     * Handle logo uploaded
     */
    handleLogoUploaded(result) {
      this.organization.logo_path = result.url
    },
  },

  watch: {
    organization(org) {
      if (org && !org.owner) {
        org.owner = {}
      }
    },
  },
}
</script>
