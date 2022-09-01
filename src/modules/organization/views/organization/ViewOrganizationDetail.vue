<template>
  <RLayout :title="organization.name">
    <RLayoutTwoCol :isLoading="isLoading">
      <template #left>
        <EcBox variant="card-1" class="width-full px-4 sm:px-10">
          <!-- Organization detail -->
          <EcText class="font-bold text-lg">Organization Detail</EcText>
          <EcBox>
            <!-- Logo -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
                <RUploadFiles :filesOf="fileOf" :entityId="entityId" :documentTitle="logoTitle" />
              </EcBox>
            </EcFlex>

            <!-- Code -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
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
              <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.name"
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
                  v-model="organization.isActive"
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
                  v-model="organization.description"
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
                  v-model="organization.friendlyUrl"
                  componentName="EcInputText"
                  :label="$t('organization.friendlyUrl')"
                  :validator="v$"
                  field="organization.address"
                  placeholder="will be generated if empty"
                  @input="v$.organization.friendlyUrl.$touch()"
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
                <EcMultiSelect :modelValue="organization.industries" :options="industries" />
              </EcBox>
            </EcFlex>

            <!-- Address -->
            <EcFlex class="flex-wrap max-w-full">
              <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
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
              <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
                <RFormInput
                  v-model="organization.owner.firstName"
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
                  v-model="organization.owner.lastName"
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
                  v-model="organization.owner.email"
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
                  v-model="organization.owner.phone"
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
            <EcButton variant="primary" @click="handleClickUpdateOrganization">
              {{ $t("organization.update") }}
            </EcButton>
            <EcButton class="ml-4" variant="tertiary-outline" @click="handleCancelUpdateOrganization">
              {{ $t("organization.cancel") }}
            </EcButton>
          </EcFlex>

          <!-- Loading -->
          <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
        </EcBox>
      </template>
      <template #right>
        <!-- Delete organization -->
        <EcBox variant="card-1" class="mb-8">
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
              <EcText class="inline text-c1-500">{{ organizationName }}</EcText>
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
import useVuelidate from "@vuelidate/core"
import { required } from "@vuelidate/validators"
import { ref } from "vue"
import EcText from "@/components/EcText/index.vue"
import RFormInput from "@/modules/core/components/common/RFormInput.vue"

export default {
  name: "ViewOrganizationDetail",
  data() {
    return {
      logoTitle: "Logo",
      isModalDeleteOpen: false,
      confirmedOrganizationName: "",

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
      entityId: "1",
      fileOf: "organization",
    }
  },
  setup() {
    const organization = ref({
      id: "dg434-dsc223-3xxx",
      logo: "",
      name: "Encoda",
      friendlyUrl: "encoda",
      description: "ENCODA ASSISTS ORGANISATIONS TO OVERCOME DELIVERY AND PLATFORM MANAGEMENT CHALLENGES.",
      address: "Suite 2, Level 5, 459 Little Collins St Melbourne Victoria",
      isActive: true,
      industries: [
        {
          name: "Manufacturing",
          value: "manu",
        },
        {
          name: "IT",
          value: "it",
        },
      ],
      owner: {
        firstName: "Mo and Matt",
        lastName: "Test",
        email: "contact@encoda.io",
        phone: "+61 433 238 082",
      },
    })

    const rules = {
      organization: {
        name: { required },
        friendlyUrl: {},
        description: {},
        address: {},
        isActive: { required },
        owner: {
          firstName: { required },
          lastName: { required },
          email: { required },
          phone: { required },
        },
      },
    }

    const { state, send } = useOrganizationDetail()
    const v$ = useVuelidate(rules, { organization })
    return {
      state,
      send,
      organization,
      v$,
    }
  },
  computed: {
    isLoading() {
      return false
    },
    matchedName() {
      return this.confirmedOrganizationName === this.organization.name
    },
    isDeleteLoading() {
      return false
    },
  },
  methods: {
    fetchOrganization() {},

    handleClickUpdateOrganization() {},
    handleCancelUpdateOrganization() {
      goto("ViewOrganizationList")
    },
    handleOpenDeleteModal() {
      this.isModalDeleteOpen = true
    },
    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
      this.confirmedOrganizationName = ""
    },
    handleDeleteOrganization() {
      this.isDeleteLoading = true
    },
  },
  components: { EcText, RFormInput },
}
</script>
