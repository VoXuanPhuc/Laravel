<template>
  <RLayout :title="$t('organization.newOrganization')">
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <!-- Organization detail -->
      <EcText class="font-bold text-lg">Organization Detail</EcText>
      <EcBox>
        <!-- Logo -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RUploadFiles
              :documentTitle="logoTitle"
              :maxFileNum="1"
              :isImage="true"
              :isUploadOnSelect="true"
              dir="logo"
              docType="logo"
              dropZoneCls="border-c0-500 border-dashed border-2 bg-cWhite p-2 md:py-4"
              @handleSingleUploadResult="handleLogoUploaded"
            />
          </EcBox>
        </EcFlex>

        <!-- Name -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.name"
              componentName="EcInputText"
              :label="$t('organization.name')"
              :validator="v$"
              field="form.name"
              @keyup="handleNameInput"
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
              :label="$t('organization.desc')"
              :validator="v$"
              field="form.description"
              @input="v$.form.description.$touch()"
            />
          </EcBox>
        </EcFlex>

        <!-- Friendly URL -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-3/12 mb-6">
            <EcFlex>
              <!-- unique id for url -->
              <RFormInput
                v-model="form.friendly_url"
                componentName="EcInputText"
                :label="$t('organization.friendlyUrl')"
                :validator="v$"
                field="form.friendly_url"
                placeholder="will be generated if empty"
                @input="v$.form.friendly_url.$touch()"
              />
            </EcFlex>
          </EcBox>
          <EcFlex class="items-center">
            <EcText>.{{ hostName }}</EcText>
            <EcSpinner v-if="isCheckingFriendlyUrl" class="ml-4" variant="basic" />
          </EcFlex>
        </EcFlex>

        <!-- Industries -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-6/12 mb-6 sm:pr-6">
            <EcText class="mb-2">Industries</EcText>
            <EcMultiSelect v-model="form.industries" :options="industries" :valueKey="'uid'" @input="v$.form.industries" />
          </EcBox>
        </EcFlex>

        <!-- Address -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.address"
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
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.owner.first_name"
              componentName="EcInputText"
              :rows="2"
              :label="$t('organization.owner.firstName')"
              :validator="v$"
              field="form.owner.first_name"
              @input="v$.form.owner.first_name.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>

      <!-- Owner last name -->
      <EcBox>
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.owner.last_name"
              componentName="EcInputText"
              :rows="2"
              :label="$t('organization.owner.lastName')"
              :validator="v$"
              field="form.owner.last_name"
              @input="v$.form.owner.last_name.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>

      <!-- Owner email -->
      <EcBox>
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.owner.email"
              componentName="EcInputText"
              :rows="2"
              :label="$t('organization.owner.email')"
              :validator="v$"
              field="form.owner.email"
              @input="v$.form.owner.email.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>

      <!-- Owner phone -->
      <EcBox>
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.owner.phone"
              componentName="EcInputText"
              :rows="2"
              :label="$t('organization.owner.phone')"
              :validator="v$"
              field="form.owner.phone"
              @input="v$.form.owner.phone.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>
      <!-- end -->
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

    <!-- Modals create success and move to portal -->
    <teleport to="#layer2">
      <EcModalSimple :isVisible="isModalCreatedOrgSuccess" variant="center-3xl">
        <EcBox class="text-center">
          <EcBox>
            <EcHeadline as="h3" variant="h3" class="text-cSuccess-500 text-md">
              <EcText class="text-lg"> {{ $t("organization.createOrganizationSuccess") }} </EcText>
              <EcText class="text-lg"> {{ $t("organization.visitNewOrganization") }} </EcText>
            </EcHeadline>

            <EcText class="text-2xl text-cError-500"> {{ this.organization.name }} </EcText>
          </EcBox>

          <!-- Actions -->
          <EcFlex class="justify-center mt-10">
            <EcButton class="mr-3" variant="tertiary-outline" @click="handleCancelViewNewOrganization">
              {{ $t("organization.no") }}
            </EcButton>
            <EcButton variant="primary" @click="handleVisitNewOrganization">
              {{ $t("organization.yes") }}
            </EcButton>
          </EcFlex>
        </EcBox>
      </EcModalSimple>
    </teleport>
  </RLayout>
</template>

<script>
import { useOrganizationCreate } from "../../use/organization/useOrganizationNew"
import { goto } from "@/modules/core/composables"
import { useIndustry } from "../../use/industry/useIndustry"
import { ref } from "vue"

export default {
  name: "ViewOrganizationNew",
  data() {
    return {
      isLoading: false,
      isCheckingFriendlyUrl: false,
      logoTitle: "Logo",
      isModalCreatedOrgSuccess: false,
    }
  },

  mounted() {
    this.fetchIndustries()
  },
  setup() {
    const { v$, form, createOrganization } = useOrganizationCreate()
    const { getIndustries } = useIndustry()
    const industries = ref([])
    const organization = ref({})

    return {
      v$,
      form,
      createOrganization,
      getIndustries,
      industries,
      organization,
    }
  },
  computed: {
    hostName() {
      return process.env.VUE_APP_HOST_NAME
    },
  },
  methods: {
    /**
     * Fetch industries
     */
    async fetchIndustries() {
      this.industries = await this.getIndustries()
    },

    /**
     * Handle name input
     */
    handleNameInput() {
      const regex = /[^a-z0-9]|\s+|\r?\n|\r/gim

      const name = this.form.name.trim()
      this.form.friendly_url = name.replaceAll(regex, "-").toLowerCase()

      this.v$.form.name.$touch()
    },

    /**
     * Creaate orgranization
     */
    async handleClickCreate() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      this.isLoading = true
      const response = await this.createOrganization(this.form)

      this.isLoading = false

      if (response && response.uid) {
        this.organization = response
        this.isModalCreatedOrgSuccess = true
      }
    },

    /**
     * Back to organization list
     */
    handleClickCancel() {
      goto("ViewOrganizationList")
    },

    /**
     * Go to Organization management
     */
    handleVisitNewOrganization() {
      goto("ViewOrganizationManagement", {
        params: {
          organizationUid: this.organization?.uid,
        },
      })
    },

    /***
     * Cancel to view new Organization
     */
    handleCancelViewNewOrganization() {
      goto("ViewOrganizationList")
    },

    /** Handle uploaded logo */
    handleLogoUploaded(result) {
      this.form.logo_path = result.url
    },
  },
}
</script>
