<template>
  <EcModalSimple :isVisible="isModalAddNewCategoryOpen" variant="center-2xl">
    <EcBox>
      <!-- Messages -->
      <EcBox class="text-center">
        <EcHeadline as="h2" class="text-4xl" variant="h2">
          {{ $t("supplier.category.title.newCategory") }}
        </EcHeadline>
      </EcBox>

      <!-- name -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="category.name"
          :label="$t('supplier.category.labels.name')"
          :validator="validator$"
          componentName="EcInputText"
          field="category.name"
          @input="validator$.category.name.$touch()"
        />
      </EcBox>

      <!-- Desc -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="category.description"
          :label="$t('supplier.category.labels.description')"
          :rows="2"
          :validator="validator$"
          componentName="EcInputLongText"
          field="category.description"
          @input="validator$.category.description.$touch()"
        />
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isLoading" class="justify-end mt-10">
        <EcButton variant="tertiary-outline" @click="handleCloseAddNewCategoryModal">
          {{ $t("supplier.buttons.cancel") }}
        </EcButton>

        <EcButton class="ml-3" variant="primary" @click="handleClickCreateCategory">
          {{ $t("supplier.buttons.create") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="items-center justify-center mt-10 h-10">
        <EcSpinner type="dots" />
      </EcFlex>
    </EcBox>
  </EcModalSimple>
</template>

<script>
import { useCategoryNew } from "@/modules/supplier/use/category/useCategoryNew"

export default {
  name: "ModalAddNewCategory",
  data() {
    return {
      isLoading: false,
    }
  },

  setup() {
    const { category, validator$, createCategory } = useCategoryNew()

    return {
      validator$,
      category,
      createCategory,
    }
  },

  emits: ["handleCloseAddNewCategoryModal", "handleCallBackAddNewCategory"],

  props: {
    isModalAddNewCategoryOpen: {
      type: Boolean,
      default: false,
    },
  },

  methods: {
    /**
     * Handle to create new category
     */
    async handleClickCreateCategory() {
      this.validator$.$touch()
      if (this.validator$.$invalid) {
        return
      }
      this.isLoading = true
      const response = await this.createCategory(this.category)
      if (response) {
        this.handleCloseAddNewCategoryModal()
        this.handleCallbackAddNewCategory()
      }
      this.isLoading = false
    },

    /**
     * Close cancel modal
     */
    handleCloseAddNewCategoryModal() {
      this.$emit("handleCloseAddNewCategoryModal")
    },

    /**
     * Call back function after create category success
     */
    handleCallbackAddNewCategory() {
      this.$emit("handleCallBackAddNewCategory")
    },
  },

  watch: {
    isModalAddNewCategoryOpen() {
      this.category.name = null
      this.category.description = ""
      this.validator$.category.$reset()
    },
  },
}
</script>

<style scoped></style>
