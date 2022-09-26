<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("resource.title.newResource") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("resource.title.resourceDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.name"
            componentName="EcInputText"
            :label="$t('resource.labels.name')"
            :validator="v$"
            field="form.name"
            @input="v$.form.name.$touch()"
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
            :label="$t('resource.labels.description')"
            :validator="v$"
            field="form.description"
            @input="v$.form.description.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Categories -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="form.category.uid"
            :label="$t('resource.category.label')"
            componentName="EcSelect"
            :options="categories"
            :valueKey="'uid'"
            :validator="v$"
            field="form.category.uid"
          />
        </EcBox>
        <EcSpinner v-if="isLoadingCategories"></EcSpinner>
      </EcFlex>

      <!-- Owners select -->
      <EcBox class="w-full mb-8">
        <EcFlex>
          <EcLabel class="text-sm"> {{ $t("resource.label.owners") }}</EcLabel>
          <EcButton variant="primary-rounded" class="h-6 ml-2" title="Did not see owner? Add new">
            <EcIcon icon="Plus" class="h-6 w-4"
          /></EcButton>
        </EcFlex>
        <!-- Owner row -->
        <EcBox class="items-center mb-2 w-full" v-for="(role, index) in form.owners" :key="index">
          <EcFlex class="items-center">
            <RFormInput
              class="w-full sm:w-6/12 sm:pr-6"
              v-model="form.owners[index].uid"
              componentName="EcSelect"
              :options="filteredOwners"
              :valueKey="'uid'"
              :nameKey="'label'"
              :validator="v$"
              field="form.owners[index].uid"
            />

            <!-- Loading owners -->
            <EcSpinner v-if="isLoadingOwners" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.owners.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveOwner(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add button -->
            <EcButton
              v-if="index == form.owners.length - 1 && form.owners.length < owners.length"
              class="ml-2"
              variant="primary-rounded"
              title="Add more owner who owns this resource"
              @click="handleAddMoreOwner"
            >
              <EcIcon icon="Plus" />
            </EcButton>
            <!-- End role select -->
          </EcFlex>

          <!-- Error message -->
          <EcBox v-if="v$.form.owners.$errors?.length > 0">
            <EcText
              class="text-sm text-cError-500 text-left"
              v-for="error in v$.form.owners.$each.$response.$errors[index].uid"
              :key="error"
            >
              {{ error.$message }}
            </EcText>
          </EcBox>
          <!-- Add new role slot -->
        </EcBox>
      </EcBox>
      <!-- End Role Select -->

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleOpenCancelModal">
          {{ $t("resource.buttons.cancel") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickNext">
          {{ $t("resource.buttons.confirm") }}
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
import { useResourceNew } from "../use/useResourceNew"
import { useResourceCategoryList } from "../use/useResourceCategoryList"
import { useOwnerList } from "../use/useOwnerList"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewActivityNew",
  data() {
    return {
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingOwners: false,
      isLoadingCategories: false,
      categories: [],
      owners: [],
    }
  },

  mounted() {
    this.fetchResourceCategories()
    this.fetchOwners()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { getOwnerList } = useOwnerList()
    const { getResourceCategoryList } = useResourceCategoryList()

    const { form, v$, createNewResource } = useResourceNew()
    return {
      createNewResource,
      getOwnerList,
      getResourceCategoryList,
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
    async handleClickNext() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }
      const { uid } = this.$route.params
      this.isLoading = true
      let response = null
      if (uid) {
        response = await this.updateActivity(this.form, uid)
      } else {
        response = await this.createNewResource(this.form)
      }
      this.isLoading = false
      if (response && response.uid) {
        goto("ViewResourceList")
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

    /**
     * Fetch roles
     */
    async fetchOwners() {
      this.isLoadingOwners = true
      const response = await this.getOwnerList()
      if (response) {
        this.owners = response
      }
      this.isLoadingOwners = false
    },

    /**
     * Fetch Categories
     */
    async fetchResourceCategories() {
      this.isLoadingCategories = true
      const response = await this.getResourceCategoryList()
      if (response && response.data) {
        this.utilities = response.data
      }
      this.isLoadingCategories = false
    },
  },
}
</script>
