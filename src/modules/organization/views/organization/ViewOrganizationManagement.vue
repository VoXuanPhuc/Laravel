<template>
  <RLayout :title="organization.name">
    <RLayoutTwoCol :isLoading="isLoading" leftCls="lg:w-6/12 lg:pr-4 mb-8" rightCls="lg:w-6/12 lg:pr-4 mb-8">
      <template #left>
        <EcBox variant="card-1" class="width-full px-4 sm:px-10">
          <!-- Add button -->
          <EcFlex class="items-center">
            <EcHeadline as="h3" variant="h3" class="text-c1-800 px-5"> {{ organization.name }}'s divisions </EcHeadline>
            <EcButton variant="primary-sm" class="mb-3 lg:mb-0" iconPrefix="PlusCircle" @click="handleClickAddDivision">
              {{ $t("organization.add") }}
            </EcButton>
          </EcFlex>

          <!-- Division list -->
          <DivisionList :listData="divisions" />
        </EcBox>
      </template>
      <template #right>
        <!-- Delete organization -->
        <EcBox variant="card-1" class="mb-8">
          <EcHeadline as="h3" variant="h3" class="text-c1-800 px-5"> Business Units </EcHeadline>
        </EcBox>
      </template>
    </RLayoutTwoCol>
  </RLayout>
</template>

<script>
import { useOrganizationDetail } from "./../../use/organization/useOrganizationDetail"
import { goto } from "@/modules/core/composables"
import useVuelidate from "@vuelidate/core"
import { required } from "@vuelidate/validators"
import { ref } from "vue"
import DivisionList from "../../components/division/DivisionList.vue"
import EcFlex from "@/components/EcFlex/index.vue"

export default {
  name: "ViewOrganizationManagement",
  data() {
    return {
      logoTitle: "Logo",
      isModalDeleteOpen: false,
      confirmedOrganizationName: "",

      divisions: [
        {
          uid: "122x",
          logo: "https://icon-library.com/images/division-icon/division-icon-9.jpg",
          name: "IT Department",
        },
        {
          uid: "122a",
          logo: "https://icon-library.com/images/division-icon/division-icon-9.jpg",
          name: "Accounting",
        },
        {
          uid: "12223",
          logo: "https://icon-library.com/images/division-icon/division-icon-9.jpg",
          name: "Human Resources",
        },
        {
          uid: "122",
          logo: "https://icon-library.com/images/division-icon/division-icon-9.jpg",
          name: "HRD1",
        },
      ],
      businessUnits: [
        {
          uid: "122",
          logo: "https://icon-library.com/images/division-icon/division-icon-9.jpg",
          name: "D1",
        },
        {
          uid: "122",
          logo: "https://icon-library.com/images/division-icon/division-icon-9.jpg",
          name: "D1",
        },
        {
          uid: "122",
          logo: "https://icon-library.com/images/division-icon/division-icon-9.jpg",
          name: "D1",
        },
        {
          uid: "122",
          logo: "https://icon-library.com/images/division-icon/division-icon-9.jpg",
          name: "D1",
        },
      ],
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
  components: { DivisionList, EcFlex },
}
</script>
