import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required, helpers } from "@vuelidate/validators"
import * as api from "../api/resourceFetcher"
import { useGlobalStore } from "@/stores/global"

export function useResourceNew() {
  const globalStore = useGlobalStore()

  const form = ref({
    category: {},
    owners: [{ uid: "" }],
  })

  const rules = {
    form: {
      name: { required },
      description: {},
      owners: {
        required,
        $each: helpers.forEach({
          uid: { required },
        }),
      },
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createNewResource = async (payload) => {
    debugger
    try {
      const { data } = await api.createNewResource(payload)
      globalStore.addSuccessToastMessage("Created new resource, redirect to resource list...")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    form,
    v$,
    createNewResource,
  }
}
