<template>
  <EcModalSimple :isVisible="isModalAddNewCategoryOpen" variant="center-2xl">
    <EcBox>
      <!-- Messages -->
      <EcBox class="text-center">
        <EcHeadline as="h2" variant="h2" class="text-4xl">
          {{ $t("resource.category.title.newCategory") }}
        </EcHeadline>
      </EcBox>

      <!-- name -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="category.name"
          :label="$t('resource.category.labels.name')"
          componentName="EcInputText"
          :validator="validator$"
          field="category.name"
          @input="validator$.category.name.$touch()"
        ></RFormInput>
      </EcBox>

      <!-- Desc -->
      <EcBox class="mt-4">
        <RFormInput
          v-model="category.description"
          :label="$t('resource.category.labels.description')"
          componentName="EcInputLongText"
          :validator="validator$"
          :rows="2"
          field="category.description"
          @input="validator$.category.description.$touch()"
        ></RFormInput>
      </EcBox>

      <!-- Actions -->
      <EcFlex v-if="!isLoading" class="justify-end mt-10">
        <EcButton variant="tertiary-outline" @click="handleCloseAddNewCategoryModal">
          {{ $t("resource.modal.buttons.cancel") }}
        </EcButton>

        <EcButton class="ml-3" variant="primary" @click="handleClickCreateCategory">
          {{ $t("resource.modal.buttons.create") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="items-center justify-center mt-10 h-10">
        <EcSpinner type="dots" />
      </EcFlex>
    </EcBox>
  </EcModalSimple>
</template>
<script>
import { useCategoryNew } from "../use/category/useCategoryNew"

export default {
  name: "ModalAddNewCategory",

  emits: ["handleCloseAddNewCategoryModal", "handleCallbackAddNewCategory"],
  data() {
    return {
      isLoading: false,
    }
  },
  props: {
    isModalAddNewCategoryOpen: {
      type: Boolean,
      default: false,
    },
  },

  mounted() {},
  setup() {
    const { category, validator$, createCategory } = useCategoryNew()
    return {
      category,
      validator$,
      createCategory,
    }
  },
  methods: {
    /**
     * Cancel add new owner
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

    handleCallbackAddNewCategory() {
      this.$emit("handleCallbackAddNewCategory")
    },
  },

  watch: {
    isModalAddNewCategoryOpen() {
      this.category.name = null
      this.category.description = null
      this.validator$.category.$reset()
    },
  },
}
</script>
