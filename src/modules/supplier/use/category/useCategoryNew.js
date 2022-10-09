import * as api from "../../api/supplierCategoryFetcher"
import { ref } from "vue"
import { useGlobalStore } from "@/stores/global"
import { maxLength, required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core/dist/index.esm"
import { useI18n } from "vue-i18n"

export function useCategoryNew() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const category = ref({
    description: "",
  })

  // validation
  const categoryRules = {
    category: {
      name: { required, maxLength: maxLength(255) },
      description: { maxLength: maxLength(1023) },
    },
  }
  const validator$ = useVuelidate(categoryRules, { category })

  /**
   * @param payload
   * @returns industry
   */
  const createCategory = async (payload) => {
    try {
      const { data } = await api.createCategory(payload)
      globalStore.addSuccessToastMessage(t("supplier.category.messages.createCategory"))
      return data
    } catch (error) {
      // return error if name has been used
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    category,
    validator$,
    createCategory,
  }
}
