<template>
  <RLayout>
    <RLayoutTwoCol :isLoading="isLoading" leftCls="lg:w-8/12 lg:pr-4 mb-8" rightCls="lg:w-4/12 lg:pr-4 mb-8">
      <template #left>
        <!-- Header -->
        <EcFlex class="items-center flex-wrap">
          <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
            <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
              {{ $t("resource.category.title.editCategory") }}
            </EcHeadline>
          </EcFlex>
        </EcFlex>

        <!-- Body -->
        <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
          <EcText class="font-bold text-lg mb-4">{{ $t("resource.category.title.categoryDetail") }}</EcText>

          <!-- First name -->
          <EcBox class="mt-4">
            <RFormInput
              v-model="form.name"
              class="w-full sm:w-8/12 sm:pr-6"
              :label="$t('resource.category.labels.name')"
              componentName="EcInputText"
              :validator="v$"
              field="form.name"
              @input="v$.form.name.$touch()"
            ></RFormInput>
          </EcBox>

          <!-- Desc -->
          <EcBox class="mt-4">
            <RFormInput
              v-model="form.description"
              class="w-full sm:w-8/12 sm:pr-6"
              :label="$t('resource.category.labels.description')"
              :rows="2"
              componentName="EcInputLongText"
              :validator="v$"
              field="form.description"
              @input="v$.form.description.$touch()"
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
        <!-- Delete Category -->
        <EcBox variant="card-1" class="mb-8 mt-20">
          <EcHeadline as="h2" variant="h2" class="text-c1-800 px-5">
            {{ $t("resource.category.labels.deleteCategory") }}
          </EcHeadline>
          <EcText class="px-5 my-6 text-c0-500 leading-normal">
            {{ $t("resource.category.labels.noteDeleteCategory") }}
          </EcText>
          <EcButton class="mx-5" variant="warning" iconPrefix="Trash" @click="handleOpenDeleteModal">
            {{ $t("resource.category.buttons.delete") }}
          </EcButton>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <!-- Modal  delete Category -->
    <teleport to="#layer1">
      <!-- Modal Delete -->
      <EcModalSimple :isVisible="isModalDeleteOpen" variant="center-2xl" @overlay-click="handleCloseDeleteModal">
        <EcBox class="text-center">
          <EcFlex class="justify-center">
            <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
          </EcFlex>

          <!-- Messages -->
          <EcBox>
            <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
              {{ $t("resource.labels.confirmToDelete") }}
            </EcHeadline>
            <!-- Category name -->
            <EcText class="text-lg font-bold">
              {{ form.name }}
            </EcText>
            <EcText class="text-c0-500 mt-4">
              {{ $t("resource.category.labels.confirmDeleteQuestion") }}
            </EcText>
          </EcBox>

          <!-- Actions -->
          <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
            <EcButton variant="warning" @click="handleDeleteCategory">
              {{ $t("resource.category.buttons.delete") }}
            </EcButton>
            <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
              {{ $t("resource.category.buttons.cancel") }}
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
import { goto } from "@/modules/core/composables"
import { useCategoryDetail } from "@/modules/resource/use/category/useCategoryDetail"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewCategoryDetail",
  data() {
    return {
      isModalDeleteOpen: false,
      isLoading: false,
      isUpdateLoading: false,
      isDeleteLoading: false,
      uid: "",
    }
  },
  mounted() {
    const { uid } = this.$route.params
    this.uid = uid

    this.fetchCategory()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { form, v$, getCategory, updateCategory, deleteCategory } = useCategoryDetail()
    return {
      getCategory,
      updateCategory,
      deleteCategory,
      form,
      v$,
      globalStore,
    }
  },
  computed: {},
  methods: {
    // =========== Category ================ //

    /**
     * Update category
     *
     */
    async handleClickConfirm() {
      this.v$.$touch()

      if (this.v$.$invalid) {
        return
      }

      const { uid } = this.$route.params

      this.isUpdateLoading = true
      await this.updateCategory(this.form, uid)
      this.isUpdateLoading = false
    },

    /**
     * Cancel update category
     */
    handleClickCancel() {
      goto("ViewCategoryList")
    },

    /**
     * Open delete category modal
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
     * Handle delete category
     */
    async handleDeleteCategory() {
      this.isDeleteLoading = true
      const { uid } = this.$route.params

      const isDeleted = await this.deleteCategory(uid)

      if (isDeleted) {
        goto("ViewCategoryList")
      }

      this.isDeleteLoading = false
    },

    // =========== PRE-LOAD -------//
    /**
     * Fetch Category
     */
    async fetchCategory() {
      this.isLoading = true
      const response = await this.getCategory(this.uid)
      if (response) {
        this.form = response
      }
      this.isLoading = false
    },
  },
}
</script>
