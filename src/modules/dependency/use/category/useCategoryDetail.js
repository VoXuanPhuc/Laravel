import * as api from "../../api/categoryFetcher"
import { useGlobalStore } from "@/stores/global"
import { required } from "@vuelidate/validators"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"

export function useCategoryDetail() {
  const globalStore = useGlobalStore()

  const form = ref({})

  const rules = {
    form: {
      name: { required },
      description: {},
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const getCategory = async (uid) => {
    try {
      const { data } = await api.fetchResourceCategory(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} payload
   * @returns
   */
  const updateCategory = async (payload, uid) => {
    try {
      const { data } = await api.updateCategory(payload, uid)
      globalStore.addSuccessToastMessage("Updated category")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  const deleteCategory = async (uid) => {
    try {
      const response = await api.deleteCategory(uid)

      return response.status === 200
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }
  return {
    getCategory,
    updateCategory,
    deleteCategory,
    form,
    v$,
  }
}
