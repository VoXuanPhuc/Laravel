import * as api from "@/readybc/composables/api/apiRoles"
import { useGlobalStore } from "@/stores/global"
import { reactive } from "vue"
import useVuelidate from "@vuelidate/core"
import { required, maxLength } from "@vuelidate/validators"

export const useRoleNew = () => {
  // Global store

  const globalStore = useGlobalStore()

  const form = reactive({
    name: "",
    label: "",
    description: "",
  })

  const rules = {
    form: {
      name: { required, maxLength: maxLength(40) },
      label: { required, maxLength: maxLength(40) },
      description: {},
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createNewRole = async (payload) => {
    try {
      const { data } = await api.createRole(payload)

      if (!data || data.error) {
        throw new Error(data.error)
      }

      globalStore.addToastMessage({ type: "success", content: "Added role successfully" })

      return data
    } catch (error) {
      globalStore.addToastMessage({ type: "error", content: error?.message })
      return error
    }
  }

  return {
    form,
    v$,
    createNewRole,
  }
}
