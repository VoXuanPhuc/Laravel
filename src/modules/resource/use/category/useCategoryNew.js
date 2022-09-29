import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/categoryFetcher"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"

export function useCategoryNew() {
  const globalStore = useGlobalStore()
  const category = ref({})

  const rules = {
    category: {
      name: { required },
      description: {},
    },
  }

  const validator$ = useVuelidate(rules, { category })

  /**
   *
   * @param {*} payload
   * @returns
   */
  async function createCategory(payload) {
    try {
      const { data } = await api.createResourceCategory(payload)

      globalStore.addSuccessToastMessage(this.$t("resource.category.messages.createCategory"))

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.category.errors.createCategory"))
    }
  }

  return {
    createCategory,
    category,
    validator$,
  }
}
