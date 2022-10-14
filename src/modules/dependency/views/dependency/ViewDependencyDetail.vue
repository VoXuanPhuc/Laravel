<template>
  <RLayout>
    <RLayoutTwoCol :isLoading="isLoading" leftCls="lg:w-8/12 lg:pr-4 mb-8" rightCls="lg:w-4/12 lg:pr-4 mb-8">
      <template #left>
        <!-- Header -->
        <EcFlex class="items-center flex-wrap">
          <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
            <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
              {{ $t("resource.title.editResource") }}
            </EcHeadline>
          </EcFlex>
        </EcFlex>

        <!-- Body -->
        <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
          <EcText class="font-bold text-lg mb-4">{{ $t("resource.title.resourceDetail") }}</EcText>
          <!-- Name -->
          <EcFlex class="flex-wrap max-w-full mb-8">
            <EcBox class="w-full lg:w-10/12 sm:pr-6">
              <RFormInput
                v-model="resource.name"
                componentName="EcInputText"
                :label="$t('resource.labels.name')"
                :validator="v$"
                field="resource.name"
                @input="v$.resource.name.$touch()"
              />
            </EcBox>
          </EcFlex>

          <!-- Desc -->
          <EcFlex class="flex-wrap max-w-full">
            <EcBox class="w-full lg:w-10/12 mb-6 sm:pr-6">
              <RFormInput
                v-model="resource.description"
                componentName="EcInputLongText"
                :rows="2"
                :label="$t('resource.labels.description')"
                :validator="v$"
                field="resource.description"
                @input="v$.resource.description.$touch()"
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
                :validator="v$"
                field="resource.status"
                @change="v$.resource.status.$touch()"
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
                :validator="v$"
                field="resource.category.uid"
                @change="v$.resource.category.uid.$touch()"
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
                <EcIcon icon="Plus" class="h-6 w-4"
              /></EcButton>
            </EcFlex>
            <!-- Owner row -->
            <EcBox class="items-center mb-2 w-full" v-for="(role, index) in resource.owners" :key="index">
              <EcFlex class="items-center">
                <RFormInput
                  class="w-full lg:w-10/12 sm:pr-6"
                  v-model="resource.owners[index].uid"
                  componentName="EcSelect"
                  :options="filteredOwners"
                  :valueKey="'uid'"
                  :nameKey="'full_name'"
                  :validator="v$"
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
              <EcBox v-if="v$.resource.owners.$errors?.length > 0">
                <EcText
                  class="text-sm text-cError-500 text-left"
                  v-for="error in v$.resource.owners.$each.$response.$errors[index].uid"
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
        <!-- Delete Resource -->
        <EcBox variant="card-1" class="mb-8 mt-20">
          <EcHeadline as="h2" variant="h2" class="text-c1-800 px-5">
            {{ $t("resource.labels.deleteResource") }}
          </EcHeadline>
          <EcText class="px-5 my-6 text-c0-500 leading-normal">
            {{ $t("resource.labels.noteDeleteResource") }}
          </EcText>
          <EcButton class="mx-5" variant="warning" iconPrefix="Trash" @click="handleOpenDeleteModal">
            {{ $t("resource.buttons.deleteResource") }}
          </EcButton>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <!-- Modal  delete resource -->
    <teleport to="#layer1">
      <ModalDeleteResource
        :isModalDeleteResourceOpen="isModalDeleteOpen"
        @handleCloseDeleteModal="handleCloseDeleteModal"
        @handleDeleteCallback="handleDeleteCallback"
      />
    </teleport>

    <!-- Modal add new resource owner -->
    <teleport to="#layer2">
      <ModalAddNewOwner
        :isModalAddNewOwnerOpen="isModalAddNewOwnerOpen"
        @handleCloseAddNewOwnerModal="handleCloseAddNewOwnerModal"
        @handleCallbackAddNewOwner="handleCallbackAddNewOwner"
      />
    </teleport>

    <!-- Modal add new resource category -->
    <teleport to="#layer3">
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
import { useResourceDetail } from "../../use/dependency/useResourceDetail"
import { useResourceStatusEnum } from "@/modules/dependency/use/dependency/useResourceStatusEnum"
import { useCategoryList } from "@/modules/dependency/use/category/useCategoryList"
import { useOwnerList } from "@/modules/dependency/use/owner/useOwnerList"
import { useGlobalStore } from "@/stores/global"
import ModalAddNewOwner from "@/modules/dependency/components/ModalAddNewOwner.vue"
import ModalDeleteResource from "../../components/ModalDeleteResource.vue"
import ModalAddNewCategory from "../../components/ModalAddNewCategory.vue"

export default {
  name: "ViewResourceDetail",
  props: {
    uid: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      isModalAddNewOwnerOpen: false,
      isModalAddNewCategoryOpen: false,
      isModalDeleteOpen: false,
      isLoading: false,
      isUpdateLoading: false,
      isLoadingOwners: false,
      isLoadingCategories: false,
      categories: [],
      owners: [],
    }
  },
  beforeMount() {
    this.fetchResource()
    this.fetchResourceCategories()
    this.fetchOwners()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { resource, v$, getResource, updateResource } = useResourceDetail()
    const { statuses } = useResourceStatusEnum()
    const { getOwnerListAll } = useOwnerList()
    const { getResourceCategoryList } = useCategoryList()

    return {
      getResource,
      updateResource,
      getOwnerListAll,
      getResourceCategoryList,
      resource,
      v$,
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
     * Create next to create activity
     *
     */
    async handleClickConfirm() {
      this.v$.$touch()
      if (this.v$.resource.$invalid) {
        return
      }

      const { uid } = this.$route.params
      this.isUpdateLoading = true

      await this.updateResource(this.resource, uid)

      this.isUpdateLoading = false
    },

    /**
     * Cancel update resource
     */
    handleClickCancel() {
      goto("ViewResourceList")
    },

    /**
     * Open Add New Owner modal
     */
    handleOpenAddNewOwnerModal() {
      this.isModalAddNewOwnerOpen = true
    },

    /**
     * Open close add new owner modal
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

    /***
     * After delete callback from modal
     */
    handleDeleteCallback() {
      goto("ViewResourceList")
    },

    // =========== PRE-LOAD -------//
    /**
     * Fetch resource
     */
    async fetchResource() {
      this.isLoading = true

      const response = await this.getResource(this.uid)
      if (response) {
        this.resource = response
        this.resource.category = response.category ?? {}
        this.resource.owners = response.owners?.length > 0 ? response.owners : [{ uid: "" }]
      }
      this.isLoading = false
    },

    /**
     * Fetch owners
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
  components: { ModalAddNewOwner, ModalDeleteResource, ModalAddNewCategory },
}
</script>
