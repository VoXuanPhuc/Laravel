<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("resource.owner.title.newOwner") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <!-- First name -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="form.first_name"
          class="w-full sm:w-6/12 sm:pr-6"
          :label="$t('resource.owner.labels.firstName')"
          componentName="EcInputText"
          :validator="v$"
          field="form.first_name"
          @input="v$.form.first_name.$touch()"
        ></RFormInput>
      </EcBox>

      <!-- Last name -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="form.last_name"
          class="w-full sm:w-6/12 sm:pr-6"
          :label="$t('resource.owner.labels.lastName')"
          componentName="EcInputText"
          :validator="v$"
          field="form.last_name"
          @input="v$.form.last_name.$touch()"
        ></RFormInput>
      </EcBox>

      <!-- Email -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="form.email"
          class="w-full sm:w-6/12 sm:pr-6"
          :label="$t('resource.owner.labels.email')"
          componentName="EcInputText"
          :validator="v$"
          field="form.email"
          @input="v$.form.email.$touch()"
        ></RFormInput>
      </EcBox>

      <!-- Invite to RBC user -->
      <EcBox class="mt-4">
        <RFormInput
          :label="$t('resource.owner.labels.inviteToUser')"
          v-model="form.is_invite"
          componentName="EcToggle"
          showLabel
          :labelTrue="$t('organization.yes')"
          :labelFalse="$t('organization.no')"
        ></RFormInput>
      </EcBox>

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("resource.owner.buttons.cancel") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickConfirm">
          {{ $t("resource.owner.buttons.confirm") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
    <!-- End actions -->
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useOwnerNew } from "@/modules/resource/use/owner/useOwnerNew"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewOwnerNew",
  data() {
    return {
      isModalAddNewOwnerOpen: false,
      isLoading: false,
      isLoadingOwners: false,
      isLoadingCategories: false,
      categories: [],
      owners: [],
    }
  },
  mounted() {},
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded

    const { form, v$, createOwner } = useOwnerNew()
    return {
      createOwner,
      form,
      v$,
      globalStore,
    }
  },
  computed: {
    /**
     * Filtered owners
     */
    filteredOwners() {
      const selectedOwnerUids = this.form.owners.map((r) => {
        return r.uid
      })
      return this.owners.map((owner) => {
        owner.disabled = selectedOwnerUids.includes(owner.uid)
        return owner
      })
    },
  },
  methods: {
    // =========== Owners ================ //
    /**
     * Add more owners
     */
    handleAddMoreOwner() {
      this.form.owners.push({ uid: "" })
    },
    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveOwner(index) {
      this.form.owners.splice(index, 1)
    },
    /**
     * Create next to create activity
     *
     */
    async handleClickConfirm() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      this.isLoading = true
      const response = await this.createOwner(this.form)
      this.isLoading = false
      if (response) {
        goto("ViewOwnerList")
      }
    },

    /**
     * Cancel add new resource
     */
    handleClickCancel() {
      goto("ViewOwnerList")
    },

    // =========== PRE-LOAD -------//
  },
}
</script>
