<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("activity.title.editActivity") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("activity.title.activityDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.name"
            componentName="EcInputText"
            :label="$t('activity.labels.name')"
            :validator="v$"
            field="form.name"
            @input="v$.form.name.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Desc -->
      <!-- <EcFlex class="flex-wrap max-w-full">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="form.description"
            componentName="EcInputLongText"
            :rows="2"
            :label="$t('activity.labels.description')"
            :validator="v$"
            field="form.description"
            @input="v$.form.description.$touch()"
          />
        </EcBox>
      </EcFlex> -->

      <!-- Status -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.status"
            :label="$t('activity.labels.status')"
            componentName="EcSelect"
            :options="statuses"
            :validator="v$"
            field="form.status"
          />
        </EcBox>
        <EcSpinner class="my-auto mb-1" v-if="isLoadingDivisions"></EcSpinner>
      </EcFlex>

      <!-- Assignee -->
      <EcFlex class="flex-wrap max-w-md mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.assignee"
            :label="$t('activity.assignee.label')"
            componentName="EcMultiSelect"
            :options="filteredUsers"
            :isSingleSelect="true"
            :valueKey="'uid'"
            :validator="v$"
            field="form.assignee"
          />
        </EcBox>
        <EcSpinner class="my-auto mb-1" v-if="isLoadingUsers"></EcSpinner>
      </EcFlex>

      <!-- Divisions -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.division.uid"
            :label="$t('activity.division.label')"
            componentName="EcSelect"
            :options="divisions"
            :valueKey="'uid'"
            :validator="v$"
            field="form.division.uid"
          />
        </EcBox>
        <EcSpinner class="my-auto mb-1" v-if="isLoadingDivisions"></EcSpinner>
      </EcFlex>

      <!-- Business Units -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.business_unit.uid"
            :label="$t('activity.bu.label')"
            componentName="EcSelect"
            :options="filteredBusinessUnits"
            :valueKey="'uid'"
            :validator="v$"
            field="form.business_unit.uid"
          />
        </EcBox>
        <EcSpinner class="my-auto mb-1" v-if="isLoadingBusinessUnits"></EcSpinner>
      </EcFlex>

      <!-- Roles select -->
      <EcBox class="w-full mb-8">
        <EcFlex class="items-center">
          <EcLabel class="text-sm"> {{ $t("activity.labels.roles") }}</EcLabel>
          <!-- Add button -->
          <EcButton
            v-if="form.roles.length < roles.length"
            class="ml-2"
            variant="primary-rounded"
            @click="handleAddMoreRole"
            v-tooltip="{ text: 'Add more role' }"
          >
            <EcIcon icon="Plus" width="16" height="16" />
          </EcButton>
        </EcFlex>

        <!-- Role row -->
        <EcBox class="items-center mb-2 w-full" v-for="(role, index) in form.roles" :key="index">
          <EcFlex class="items-center">
            <RFormInput
              class="w-full sm:w-6/12 sm:pr-6"
              v-model="form.roles[index].uid"
              componentName="EcSelect"
              :options="filteredRoles"
              :valueKey="'uid'"
              :nameKey="'label'"
              :validator="v$"
              field="form.roles[index].uid"
            />

            <!-- Loading roles -->
            <EcSpinner v-if="isLoadingRoles" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton v-if="form.roles.length > 1" class="ml-2" variant="tertiary-rounded" @click="handleRemoveRole(index)">
              <EcIcon class="text-c1-400" icon="X" width="16" height="16" />
            </EcButton>

            <!-- End role select -->
          </EcFlex>

          <!-- Error message -->
          <EcBox v-if="v$.form.roles.$errors?.length > 0">
            <EcText
              class="text-sm text-cError-500 text-left"
              v-for="error in v$.form.roles.$each.$response.$errors[index].uid"
              :key="error"
            >
              {{ error.$message }}
            </EcText>
          </EcBox>
          <!-- Add new role slot -->
        </EcBox>
      </EcBox>
      <!-- End Role Select -->

      <!-- Alternative roles select -->
      <EcBox class="w-full mb-8" v-if="filteredAlternativeRoles.length > 0">
        <EcFlex class="items-center">
          <EcLabel class="text-sm"> {{ $t("activity.labels.alternative_roles") }}</EcLabel>
          <!-- Add button -->
          <EcButton
            v-if="form.alternative_roles.length < filteredAlternativeRoles.length"
            class="ml-2"
            variant="primary-rounded"
            @click="handleAddMoreAlternativeRole"
            v-tooltip="{ text: 'Add more alternative role' }"
          >
            <EcIcon icon="Plus" width="16" height="16" />
          </EcButton>
        </EcFlex>
        <!-- Role row -->
        <EcBox class="items-center mb-2 w-full" v-for="(role, index) in form.alternative_roles" :key="index">
          <EcFlex class="items-center">
            <RFormInput
              class="w-full sm:w-6/12 sm:pr-6"
              v-model="form.alternative_roles[index].uid"
              componentName="EcSelect"
              :options="filteredAlternativeRoles"
              :valueKey="'uid'"
              :nameKey="'label'"
              :validator="v$"
              field="form.alternative_roles[index].uid"
            />

            <!-- Loading roles -->
            <EcSpinner v-if="isLoadingRoles" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="form.alternative_roles.length > 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveAlternativeRole(index)"
            >
              <EcIcon class="text-c1-400" icon="X" width="16" height="16" />
            </EcButton>

            <!-- End alternative role select -->
          </EcFlex>

          <!-- Error message -->
          <EcBox v-if="v$.form.alternative_roles.$errors?.length > 0">
            <EcText
              class="text-sm text-cError-500 text-left"
              v-for="error in v$.form.alternative_roles.$each.$response.$errors[index].uid"
              :key="error"
            >
              {{ error.$message }}
            </EcText>
          </EcBox>
          <!-- Add new role slot -->
        </EcBox>
      </EcBox>
      <!-- End Alternative Roles Select -->

      <!-- Min people -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.min_people"
            componentName="EcInputNumberStepper"
            :label="$t('activity.labels.min_people')"
            :step="1"
            :min="1"
            :max="100"
            :validator="v$"
            field="form.min_people"
            @input="v$.form.min_people.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- WFH  -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.is_remote"
            componentName="EcToggle"
            :label="$t('activity.labels.wfh')"
            showLabel
            :labelTrue="$t('organization.yes')"
            :labelFalse="$t('organization.no')"
          />
        </EcBox>
      </EcFlex>

      <!-- Utilities -->
      <EcBox class="w-full mb-8">
        <EcFlex class="items-center">
          <EcLabel class="text-sm"> {{ $t("activity.labels.utilities") }}</EcLabel>
          <!-- Add button -->
          <EcButton
            v-if="form.utilities.length < utilities.length"
            class="ml-2"
            variant="primary-rounded"
            @click="handleAddMoreUtility"
            v-tooltip="{ text: 'Add more utility' }"
          >
            <EcIcon icon="Plus" width="16" height="16" />
          </EcButton>
        </EcFlex>

        <!-- Utility row -->
        <EcBox class="items-center mb-2 w-full" v-for="(role, index) in form.utilities" :key="index">
          <EcFlex class="items-center">
            <RFormInput
              class="w-full sm:w-6/12 sm:pr-6"
              v-model="form.utilities[index].uid"
              componentName="EcSelect"
              :options="filteredUtilities"
              :valueKey="'uid'"
              :validator="v$"
              field="form.utilities[index].uid"
            />

            <!-- Loading utilities -->
            <EcSpinner v-if="isLoadingUtilities" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="form.utilities.length > 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveUtility(index)"
            >
              <EcIcon class="text-c1-400" icon="X" width="16" height="16" />
            </EcButton>

            <!-- End role select -->
          </EcFlex>

          <!-- Error message -->
          <EcBox v-if="v$.form.utilities.$errors?.length > 0">
            <EcText
              class="text-sm text-cError-500 text-left"
              v-for="error in v$.form.utilities.$each.$response.$errors[index].uid"
              :key="error"
            >
              {{ error.$message }}
            </EcText>
          </EcBox>
          <!-- Add new role slot -->
        </EcBox>
      </EcBox>
      <!-- End Utilities Select -->

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handdleClickBack">
          {{ $t("activity.buttons.back") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickNext">
          {{ $t("activity.buttons.next") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
    <!-- End actions -->

    <!-- Modals -->
    <teleport to="#layer2">
      <ModalCancelAddActivity
        :isModalCancelOpen="isModalCancelOpen"
        @handleOpenCancelModal="handleOpenCancelModal"
        @handleCloseCancelModal="handleCloseCancelModal"
      />
    </teleport>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useUserList } from "@/modules/user/use/useUserList"
import { useRoleList } from "@/modules/user/use/useRoleList"
import { useActivityNew } from "../use/useActivityNew"
import { useActivityDetail } from "../use/useActivityDetail"
import { useUtilities } from "@/readybc/composables/use/useUtilities"
import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"
import { useDivisionList } from "@/modules/organization/use/division/useDivisionList"
import { useGlobalStore } from "@/stores/global"
import { useBusinessUnitList } from "@/modules/organization/use/business_unit/useBusinessUnitList"
import isEmpty from "lodash.isempty"
import EcFlex from "@/components/EcFlex/index.vue"

export default {
  name: "ViewActivityDetail",
  data() {
    return {
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingUsers: false,
      isLoadingRoles: false,
      isLoadingUtilities: false,
      isLoadingDivisions: false,
      isLoadingBusinessUnits: false,
      users: [],
      roles: [],
      utilities: [],
      divisions: [],
      businessUnits: [],
    }
  },
  mounted() {
    this.fetchActivity()
    this.fetchUsers()
    this.fetchRoles()
    this.fetchUtilities()
    this.fetchDivisions()
    this.fetchBusinessUnits()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { getActivity, updateActivity, statuses } = useActivityDetail()
    const { getDivisions } = useDivisionList()
    const { getBusinessUnits } = useBusinessUnitList()

    const { fetchUserList } = useUserList()
    const { getRoles } = useRoleList()
    const { getUtilities } = useUtilities()
    const { form, v$ } = useActivityNew()

    return {
      // Pre load
      getActivity,
      updateActivity,
      fetchUserList,
      getRoles,
      getUtilities,
      getDivisions,
      getBusinessUnits,
      statuses,
      form,
      v$,
      globalStore,
    }
  },

  computed: {
    // Users
    filteredUsers() {
      return this.users?.map((item) => {
        return {
          name: [item.firstName, item.lastName].join(" "),
          uid: item.id,
        }
      })
    },
    /**
     * Filtered roles
     */
    filteredRoles() {
      return this.roles.map((role) => {
        // role.disabled = this.form.roles.includes(role.uid)

        return role
      })
    },

    /**
     * Filtered alternative roles
     */
    filteredAlternativeRoles() {
      return this.filteredRoles
        .filter((role) => {
          return !role.disabled
        })
        .map((role) => {
          // role.disabled = this.form.alternative_roles.includes(role.uid)

          return role
        })
    },

    /**
     * Filter utitlities
     */
    filteredUtilities() {
      return this.utilities.map((utility) => {
        utility.disabled = this.form.utilities.includes(utility.uid)

        return utility
      })
    },

    /**
     * Filter BU
     */
    filteredBusinessUnits() {
      if (isEmpty(this.form.division?.uid)) {
        return this.businessUnits
      }

      return this.businessUnits.filter((bu) => {
        return bu.division?.uid === this.form.division?.uid
      })
    },
  },
  methods: {
    /**
     * Creaate Activity
     */
    async handleClickCreate() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }
      this.isLoading = true
      // const response = await this.createActivity(this.form)
      this.isLoading = false
    },
    // =========== ROLES ================ //
    /**
     * Add more role
     */
    handleAddMoreRole() {
      this.form.roles.push({ uid: "" })
    },
    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveRole(index) {
      this.form.roles.splice(index, 1)
    },
    // =========== ALTERNATIVE ROLES ================ //
    /**
     * Add more alternative role
     */
    handleAddMoreAlternativeRole() {
      this.form.alternative_roles.push({ uid: "" })
    },
    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveAlternativeRole(index) {
      this.form.alternative_roles.splice(index, 1)
    },
    // =========== UTILITIES ================ //
    /**
     * Add more alternative role
     */
    handleAddMoreUtility() {
      this.form.utilities.push({ uid: "" })
    },
    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveUtility(index) {
      this.form.utilities.splice(index, 1)
    },

    /**
     * Back to list
     */
    handdleClickBack() {
      goto("ViewActivityList")
    },

    /**
     * Create next to create activity
     *
     */
    async handleClickNext() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      const { uid } = this.$route.params

      this.isLoading = true

      const response = await this.updateActivity(this.form, uid)

      this.isLoading = false

      if (response && response.uid) {
        goto("ViewActivityUpdateRemoteAccess", {
          params: {
            uid: response.uid,
            isUpdate: true,
          },
        })
      }
    },

    /**
     * Open cancel modal
     */
    handleOpenCancelModal() {
      this.isModalCancelOpen = true
    },

    /**
     * Open cancel modal
     */
    handleCloseCancelModal() {
      this.isModalCancelOpen = false
    },

    // =========== PRE-LOAD -------//

    async fetchActivity() {
      const { uid } = this.$route.params
      this.isLoading = true

      const response = await this.getActivity(uid, [
        "assignee",
        "division",
        "businessUnit",
        "roles",
        "alternativeRoles",
        "utilities",
      ])

      if (response && response.uid) {
        this.transformData(response)
      }
      this.isLoading = false
    },

    /**
     *
     * @param {*} response
     */
    transformData(response) {
      // Activity detail

      this.form.name = response.name
      this.form.status = response.status
      this.form.min_people = response.min_people
      this.form.is_remote = response.is_remote

      if (response.assignee) {
        this.form.assignee = response.assignee
      }

      if (response.division) {
        this.form.division = response.division
      }

      if (response.business_unit) {
        this.form.business_unit = response.business_unit
      }

      // Roles
      if (response.roles.length > 0) {
        this.form.roles = response.roles
      }

      // Alternative Roles
      if (response.alternative_roles.length > 0) {
        this.form.alternative_roles = response.alternative_roles
      }

      // Utilities
      if (response.utilities.length > 0) {
        this.form.utilities = response.utilities
      }
    },

    /**
     * Fetch users
     */
    async fetchUsers() {
      this.isLoadingUsers = true
      const response = await this.fetchUserList()
      if (response) {
        this.users = response
      }
      this.isLoadingUsers = false
    },

    /**
     * Fetch roles
     */
    async fetchRoles() {
      this.isLoadingRoles = true
      const response = await this.getRoles()
      if (response) {
        this.roles = response
      }
      this.isLoadingRoles = false
    },

    /**
     * Fetch Utilities
     */
    async fetchUtilities() {
      this.isLoadingUtilities = true
      const response = await this.getUtilities()
      if (response && response.data) {
        this.utilities = response.data
      }
      this.isLoadingUtilities = false
    },

    /**
     * Fetch Divisions
     */
    async fetchDivisions() {
      this.isLoadingDivisions = true
      const response = await this.getDivisions()
      if (response && response.data) {
        this.divisions = response.data
      }
      this.isLoadingDivisions = false
    },

    /**
     * Fetch BU
     */
    async fetchBusinessUnits() {
      this.isLoadingBusinessUnits = true
      const response = await this.getBusinessUnits()

      this.isLoading = false

      if (response && response.data) {
        this.businessUnits = response.data
      }
      this.isLoadingBusinessUnits = false
    },
  },
  components: { ModalCancelAddActivity, EcFlex },
}
</script>
