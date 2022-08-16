import { reactive } from "vue"
import { required, email, maxLength, alpha } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import * as api from "../api/userFetcher"
import { goto } from "@/modules/core/composables"

export function useUserNew() {
  const globalStore = useGlobalStore()

  const form = reactive({
    firstName: "",
    lastName: "",
    username: "",
    roleId: "",
  })

  const rules = {
    form: {
      firstName: { required, alpha },
      lastName: { required, alpha },
      username: { required, email, maxLength: maxLength(225) },
      roleId: { required },
    },
  }

  // Validate
  const v$ = useVuelidate(rules, { form })

  async function createNewUser(form) {
    const payload = {
      username: form.username,
      firstName: form.firstName,
      lastName: form.lastName,
      roleId: "",
    }
    try {
      const response = await api.createUser(payload)

      if (!response || response.error) {
        throw new Error(response.error)
      }

      globalStore.addToastMessage({ type: "success", content: "Created success, loading..." })

      goto("ViewUserDetail", {
        params: {
          userId: response?.data?.id,
        },
      })
    } catch (error) {
      globalStore.addToastMessage({ type: "error", content: error?.message })
    }
  }

  return {
    form,
    v$,
    createNewUser,
  }
}
