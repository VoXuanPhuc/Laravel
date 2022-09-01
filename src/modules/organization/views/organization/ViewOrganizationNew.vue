<template>
  <RLayout :title="$t('organization.newOrganization')">
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <!-- Organization detail -->
      <EcText class="font-bold text-lg">Organization Detail</EcText>
      <EcBox>
        <!-- Logo -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RUploadFiles :filesOf="fileOf" :entityId="entityId" :documentTitle="logoTitle" />
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
              @input="v$.form.name.$touch()"
            />
          </EcBox>
        </EcFlex>

        <!-- Status -->
        <EcFlex class="flex-wrap max-w-md">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.isActive"
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
          <EcBox class="w-2/12 mb-6">
            <RFormInput
              v-model="form.friendlyUrl"
              componentName="EcInputText"
              :label="$t('organization.friendlyUrl')"
              :validator="v$"
              field="form.address"
              placeholder="will be generated if empty"
              @input="v$.form.address.$touch()"
            />
          </EcBox>
          <EcFlex class="items-center">
            <EcText>.readybc.com</EcText>
          </EcFlex>
        </EcFlex>

        <!-- Industries -->
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-6/12 mb-6 sm:pr-6">
            <EcText class="mb-2">Industries</EcText>
            <EcMultiSelect :options="industries" />
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
              v-model="form.owner.firstName"
              componentName="EcInputText"
              :rows="2"
              :label="$t('organization.owner.firstName')"
              :validator="v$"
              field="form.owner.firstName"
              @input="v$.form.owner.firstName.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>

      <!-- Owner last name -->
      <EcBox>
        <EcFlex class="flex-wrap max-w-full">
          <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
            <RFormInput
              v-model="form.owner.lastName"
              componentName="EcInputText"
              :rows="2"
              :label="$t('organization.owner.lastName')"
              :validator="v$"
              field="form.owner.lastName"
              @input="v$.form.owner.lastName.$touch()"
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
      <EcModalSimple :isVisible="isModalCreatedOrgSuccess" variant="center-3xl" @overlay-click="handleCancelViewNewOrganization">
        <EcBox class="text-center">
          <EcBox>
            <EcHeadline as="h3" variant="h3" class="text-cSuccess-500 text-md">
              <EcText class="text-lg">Organisation successfully created!</EcText>
              <EcText class="text-lg"> Do you want to visit the client portal now? </EcText>
            </EcHeadline>

            <EcText class="text-2xl text-cError-500"> New Organization name </EcText>
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
import EcBox from "@/components/EcBox/index.vue"
import EcText from "@/components/EcText/index.vue"

export default {
  name: "ViewOrganizationNew",
  data() {
    return {
      logoTitle: "Logo",
      industries: [
        {
          name: "Manufacturing",
          value: "manu",
        },
        {
          name: "IT",
          value: "it",
        },
        {
          name: "Retail",
          value: "retail",
        },
      ],
      isModalCreatedOrgSuccess: false,
    }
  },
  setup() {
    const { v$, form } = useOrganizationCreate()
    const fileOf = "organization"
    const entityId = "1"
    return {
      v$,
      form,
      fileOf,
      entityId,
    }
  },
  computed: {
    isLoading() {
      return false
    },
  },
  methods: {
    handleClickCreate() {
      this.v$.$touch()
      if (this.v$.$invalid) return
      console.log("create")
      this.isModalCreatedOrgSuccess = true
    },

    handleClickCancel() {
      goto("ViewOrganizationList")
    },

    handleVisitNewOrganization() {},
    handleCancelViewNewOrganization() {
      this.isModalCreatedOrgSuccess = false
    },
  },
  components: { EcBox, EcText },
}
</script>
