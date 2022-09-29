<template>
  <RLayout>
    <RLayoutTwoCol :isLoading="isLoading" leftCls="lg:w-8/12 lg:pr-4 mb-8" rightCls="lg:w-4/12 lg:pr-4 mb-8">
      <template #left>
        <!-- Header -->
        <EcFlex class="items-center flex-wrap">
          <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
            <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
              {{ $t("resource.owner.title.editOwner") }}
            </EcHeadline>
          </EcFlex>
        </EcFlex>

        <!-- Body -->
        <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
          <EcText class="font-bold text-lg mb-4">{{ $t("resource.owner.title.ownerDetail") }}</EcText>

          <!-- First name -->
          <EcBox class="mt-4">
            <RFormInput
              v-model="form.first_name"
              class="w-full sm:w-8/12 sm:pr-6"
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
              class="w-full sm:w-8/12 sm:pr-6"
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
              class="w-full sm:w-8/12 sm:pr-6"
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
          <EcFlex v-if="!isUpdateLoading" class="mt-6">
            <EcButton variant="tertiary-outline" @click="handleClickCancel">
              {{ $t("resource.buttons.back") }}
            </EcButton>

            <EcButton variant="primary" class="ml-4" @click="handleClickConfirm">
              {{ $t("resource.buttons.confirm") }}
            </EcButton>
          </EcFlex>

          <!-- Loading -->
          <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
        </EcBox>
        <!-- End actions -->
      </template>

      <template #right>
        <!-- Delete Owner -->
        <EcBox variant="card-1" class="mb-8 mt-20">
          <EcHeadline as="h2" variant="h2" class="text-c1-800 px-5">
            {{ $t("resource.owner.labels.deleteOwner") }}
          </EcHeadline>
          <EcText class="px-5 my-6 text-c0-500 leading-normal">
            {{ $t("resource.owner.labels.noteDeleteOwner") }}
          </EcText>
          <EcButton class="mx-5" variant="warning" iconPrefix="Trash" @click="handleOpenDeleteModal">
            {{ $t("resource.buttons.deleteResource") }}
          </EcButton>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <!-- Modal  delete owner -->
    <teleport to="#layer1">
      <ModalDeleteOwner
        :ownerUid="uid"
        :isModalDeleteOwnerOpen="isModalDeleteOpen"
        @handleCloseDeleteModal="handleCloseDeleteModal"
        @handleDeleteCallback="handleDeleteCallback"
      />
    </teleport>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useOwnerDetail } from "@/modules/resource/use/owner/useOwnerDetail"
import { useGlobalStore } from "@/stores/global"
import ModalDeleteOwner from "../../components/ModalDeleteOwner.vue"

export default {
  name: "ViewOwnerDetail",
  data() {
    return {
      isModalAddNewOwnerOpen: false,
      isModalDeleteOpen: false,
      isLoading: false,
      isUpdateLoading: false,
      uid: "",
    }
  },
  beforeMount() {
    const { uid } = this.$route.params
    this.uid = uid

    this.fetchOwner()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { form, v$, getResourceOwner, updateResourceOwner } = useOwnerDetail()
    return {
      getResourceOwner,
      updateResourceOwner,
      form,
      v$,
      globalStore,
    }
  },
  computed: {},
  methods: {
    // =========== Owners ================ //

    /**
     * Create next to create activity
     *
     */
    async handleClickConfirm() {
      this.v$.$touch()

      if (this.v$.$invalid) {
        // return
      }

      const { uid } = this.$route.params
      this.isUpdateLoading = true
      await this.updateResourceOwner(this.form, uid)
      this.isUpdateLoading = false
    },

    /**
     * Cancel update resource
     */
    handleClickCancel() {
      goto("ViewOwnerList")
    },

    /**
     * Open delete resource modal
     */
    handleOpenDeleteModal() {
      this.isModalDeleteOpen = true
    },

    /**
     * Open delete resource modal
     */
    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
    },

    /**
     * Handle delete callback
     */
    handleDeleteCallback() {
      goto("ViewOwnerList")
    },

    // =========== PRE-LOAD -------//
    /**
     * Fetch owners
     */
    async fetchOwner() {
      this.isLoading = true
      const response = await this.getResourceOwner(this.uid)
      if (response) {
        this.form = response
      }
      this.isLoading = false
    },
  },
  components: { ModalDeleteOwner },
}
</script>
