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
            v-model="resource.name"
            componentName="EcInputText"
            :label="$t('resource.labels.name')"
            :validator="vldator$"
            field="resource.name"
            @input="vldator$.resource.name.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Desc -->
      <EcFlex class="flex-wrap max-w-full">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="resource.description"
            componentName="EcInputLongText"
            :rows="2"
            :label="$t('resource.labels.description')"
            :validator="vldator$"
            field="resource.description"
            @input="vldator$.resource.description.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Status -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <!-- select box -->
          <RFormInput
            class="w-full"
            v-model="resource.status"
            componentName="EcSelect"
            :label="$t('resource.labels.status')"
            :allowSelectNothing="false"
            :options="statuses"
            :validator="vldator$"
            field="resource.status"
            @change="vldator$.resource.status.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Categories -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <EcFlex class="mb-3">
            <EcLabel class="text-sm"> {{ $t("resource.category.label") }}</EcLabel>
            <EcButton
              variant="primary-rounded"
              class="h-6 ml-2"
              title="Did not see category? Add new"
              @click="handleOpenAddNewCategoryModal"
            >
              <EcIcon icon="Plus" class="h-6 w-4" />
            </EcButton>
          </EcFlex>
          <!-- select box -->
          <RFormInput
            class="w-full"
            v-model="resource.category.uid"
            componentName="EcSelect"
            :allowSelectNothing="false"
            :options="categories"
            :valueKey="'uid'"
            :validator="vldator$"
            field="resource.category.uid"
            @change="vldator$.resource.category.uid.$touch()"
          />
        </EcBox>
        <EcSpinner v-if="isLoadingCategories"></EcSpinner>
      </EcFlex>

      <!-- Owners select -->
      <EcBox class="w-full mb-8">
        <EcFlex>
          <EcLabel class="text-sm"> {{ $t("resource.labels.owners") }}</EcLabel>
          <EcButton
            variant="primary-rounded"
            class="h-6 ml-2"
            title="Did not see owner? Add new"
            @click="handleOpenAddNewOwnerModal"
          >
            <EcIcon icon="Plus" class="h-6 w-4" />
          </EcButton>
        </EcFlex>

        <!-- Owner row -->
        <EcBox class="items-center mb-2 w-full" v-for="(role, index) in resource.owners" :key="index">
          <EcFlex class="items-center">
            <RFormInput
              class="w-full sm:w-6/12 sm:pr-6"
              v-model="resource.owners[index].uid"
              componentName="EcSelect"
              :options="filteredOwners"
              :valueKey="'uid'"
              :nameKey="'full_name'"
              :validator="vldator$"
              field="resource.owners[index].uid"
            />

            <!-- Loading owners -->
            <EcSpinner v-if="isLoadingOwners" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== resource.owners.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveOwner(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add button -->
            <EcButton
              v-if="index == resource.owners.length - 1 && resource.owners.length < owners.length"
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
          <EcBox v-if="vldator$.resource.owners.$errors?.length > 0">
            <EcText
              class="text-sm text-cError-500 text-left"
              v-for="error in vldator$.resource.owners.$each.$response.$errors[index].uid"
              :key="error"
            >
              {{ error.$message }}
            </EcText>
          </EcBox>
          <!-- Add new owner slot -->
        </EcBox>
      </EcBox>
      <!-- End Owners Select -->

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("resource.buttons.cancel") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickConfirm">
          {{ $t("resource.buttons.confirm") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
    <!-- End actions -->

    <!-- Modal add new resource owner -->
    <teleport to="#layer1">
      <ModalAddNewOwner
        :isModalAddNewOwnerOpen="isModalAddNewOwnerOpen"
        @handleCloseAddNewOwnerModal="handleCloseAddNewOwnerModal"
        @handleCallbackAddNewOwner="handleCallbackAddNewOwner"
      ></ModalAddNewOwner>
    </teleport>

    <!-- Modal add new resource category -->
    <teleport to="#layer2">
      <ModalAddNewCategory
        :isModalAddNewCategoryOpen="isModalAddNewCategoryOpen"
        @handleCloseAddNewCategoryModal="handleCloseAddNewCategoryModal"
        @handleCallbackAddNewCategory="handleCallbackAddNewCategory"
      />
    </teleport>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useResourceNew } from "@/modules/resource/use/resource/useResourceNew"
import { useCategoryList } from "@/modules/resource/use/category/useCategoryList"
import { useResourceStatusEnum } from "@/modules/resource/use/resource/useResourceStatusEnum"
import { useOwnerList } from "@/modules/resource/use/owner/useOwnerList"
import { useGlobalStore } from "@/stores/global"
import ModalAddNewOwner from "@/modules/resource/components/ModalAddNewOwner.vue"
import ModalAddNewCategory from "../../components/ModalAddNewCategory.vue"

export default {
  name: "ViewResourceNew",
  data() {
    return {
      isModalAddNewOwnerOpen: false,
      isModalAddNewCategoryOpen: false,
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
    const { getOwnerListAll } = useOwnerList()
    const { getResourceCategoryList } = useCategoryList()
    const { resource, vldator$, createNewResource } = useResourceNew()
    const { statuses } = useResourceStatusEnum()

    return {
      createNewResource,
      getOwnerListAll,
      getResourceCategoryList,
      resource,
      vldator$,
      globalStore,
      statuses,
    }
  },
  computed: {
    /**
     * Filtered owners
     */
    filteredOwners() {
      const selectedOwnerUids = this.resource.owners.map((r) => {
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
      this.resource.owners.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveOwner(index) {
      this.resource.owners.splice(index, 1)
    },

    /**
     * Create resource
     *
     */
    async handleClickConfirm() {
      this.vldator$.$touch()
      if (this.vldator$.resource.$invalid) {
        return
      }

      this.isLoading = true

      const response = await this.createNewResource(this.resource)

      this.isLoading = false
      if (response) {
        goto("ViewResourceList")
      }
    },

    /**
     * Cancel add new resource
     */
    handleClickCancel() {
      goto("ViewResourceList")
    },

    /**
     * Open Add New Onwner modal
     */
    handleOpenAddNewOwnerModal() {
      this.isModalAddNewOwnerOpen = true
    },

    /**
     * Open cancel Add new Owner modal
     */
    handleCloseAddNewOwnerModal() {
      this.isModalAddNewOwnerOpen = false
    },

    /**
     * Open Add New Category modal
     */
    handleOpenAddNewCategoryModal() {
      this.isModalAddNewCategoryOpen = true
    },

    /**
     * Open cancel Add new Category modal
     */
    handleCloseAddNewCategoryModal() {
      this.isModalAddNewCategoryOpen = false
    },

    /**
     * Callback after add new owner
     */
    handleCallbackAddNewOwner() {
      this.fetchOwners()
    },

    /**
     * Callback after add new category
     */
    handleCallbackAddNewCategory() {
      this.fetchResourceCategories()
    },

    // =========== PRE-LOAD -------//
    /**
     * Fetch roles
     */
    async fetchOwners() {
      this.isLoadingOwners = true
      const response = await this.getOwnerListAll()
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
      if (response) {
        this.categories = response
      }
      this.isLoadingCategories = false
    },
  },
  components: { ModalAddNewOwner, ModalAddNewCategory },
}
</script>
