<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("activity.title.newActivity") }}
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

      <!-- Roles -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full">
          <EcLabel class="text-sm"> {{ $t("activity.labels.roles") }}</EcLabel>

          <!-- Role select -->
          <EcFlex class="items-center mb-2 w-full" v-for="(role, index) in form.roles" :key="index">
            <EcBox class="w-full sm:w-6/12 sm:pr-6">
              <RFormInput
                v-model="form.roles[index]"
                componentName="EcSelect"
                :options="filteredRoles"
                :valueKey="'uid'"
                :nameKey="'label'"
                :validator="v$"
                field="form.roles[index]"
                @input="v$.form.roles.$touch()"
              />

              <!-- Add new role slot -->
            </EcBox>

            <!-- Loading roles -->
            <EcSpinner v-if="isLoadingRoles" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.roles.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveRole(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add button -->
            <EcButton
              v-if="index == form.roles.length - 1 && form.roles.length < roles.length"
              class="ml-2"
              variant="primary-rounded"
              @click="handleAddMoreRole"
            >
              <EcIcon icon="Plus" />
            </EcButton>
          </EcFlex>
          <!-- End role select -->
        </EcBox>

        <!-- Add more roles -->
      </EcFlex>
      <!-- End add more role -->

      <!-- Alternative Roles -->
      <EcFlex v-if="filteredAlternativeRoles.length > 0" class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full">
          <EcLabel class="text-sm"> {{ $t("activity.labels.alternative_roles") }}</EcLabel>

          <!-- Alternative Role select -->
          <EcFlex class="items-center w-full mt-2" v-for="(role, index) in form.alternative_roles" :key="index">
            <EcBox class="w-full sm:w-6/12 sm:pr-6">
              <RFormInput
                componentName="EcSelect"
                :options="filteredAlternativeRoles"
                :valueKey="'uid'"
                :nameKey="'label'"
                field="form.alternative_roles[index]"
              />
              <!-- Add new alternative role slot -->
            </EcBox>

            <!-- Loading roles -->
            <EcSpinner v-if="isLoadingRoles" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.alternative_roles.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveAlternativeRole(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add button -->
            <EcButton
              v-if="index == form.alternative_roles.length - 1 && form.alternative_roles.length < filteredAlternativeRoles.length"
              class="ml-2"
              variant="primary-rounded"
              @click="handleAddMoreAlternativeRole"
            >
              <EcIcon icon="Plus" />
            </EcButton>
          </EcFlex>
          <!-- End role select -->
        </EcBox>

        <!-- Add more roles -->
      </EcFlex>
      <!-- End add more alternative role -->

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
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full">
          <EcLabel class="text-sm"> {{ $t("activity.labels.utilities") }}</EcLabel>

          <!-- Utilities select -->
          <EcFlex class="items-center w-full mt-2" v-for="(role, index) in form.utilities" :key="index">
            <EcBox class="w-full sm:w-6/12 sm:pr-6">
              <RFormInput componentName="EcSelect" :options="filteredUtilities" :valueKey="'uid'" />
              <!-- Add new role slot -->
            </EcBox>

            <!-- Loading utilities -->
            <EcSpinner v-if="isLoadingUtilities" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.utilities.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveUtility(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add  more utility button -->
            <EcButton
              v-if="index == form.utilities.length - 1 && form.utilities.length < utilities.length"
              class="ml-2"
              variant="primary-rounded"
              @click="handleAddMoreUtility"
            >
              <EcIcon icon="Plus" />
            </EcButton>

            <!-- End Add  more utility button -->
          </EcFlex>
          <!-- End role select -->
        </EcBox>
      </EcFlex>
      <!-- End add more alternative role -->

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleOpenCancelModal">
          {{ $t("activity.buttons.cancel") }}
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
import { useRoleList } from "@/modules/user/use/useRoleList"
import { useActivityNew } from "../use/useActivityNew"
import { useUtilities } from "../use/useUtilities"
import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"

export default {
  name: "ViewActivityNew",
  data() {
    return {
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingRoles: false,
      isLoadingUtilities: false,
      roles: [],
      utilities: [],
    }
  },
  mounted() {
    this.fetchRoles()
    this.fetchUtilities()
  },
  setup() {
    // Pre-loaded
    const { getRoles } = useRoleList()
    const { getUtilities } = useUtilities()
    const { form, v$ } = useActivityNew()
    // const roles = ref([])
    return {
      // Pre load
      getRoles,
      getUtilities,
      form,
      v$,
      // roles,
    }
  },

  computed: {
    /**
     * Filtered roles
     */
    filteredRoles() {
      return this.roles.map((role) => {
        role.disabled = this.form.roles.includes(role.uid)

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
          role.disabled = this.form.alternative_roles.includes(role.uid)

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
      this.form.roles.push("")
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
      this.form.alternative_roles.push("")
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
      this.form.utilities.push("")
    },
    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveUtility(index) {
      this.form.utilities.splice(index, 1)
    },

    /**
     * Create next to create activity
     *
     */
    handleClickNext() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }
      goto("ViewActivityNewStep2")
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
      if (response) {
        this.utilities = response
      }
      this.isLoadingUtilities = false
    },
  },
  components: { ModalCancelAddActivity },
}
</script>
