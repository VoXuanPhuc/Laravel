import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required, helpers } from "@vuelidate/validators"
import * as api from "../../api/resourceFetcher"
import { useGlobalStore } from "@/stores/global"

export function useResourceNew() {
  const globalStore = useGlobalStore()

  const resource = ref({
    category: {},
    owners: [{ uid: "" }],
  })

  const rules = {
    resource: {
      name: { required },
      description: {},
      status: { required },
      category: {
        uid: { required },
      },
      owners: {
        $each: helpers.forEach({
          uid: { required },
        }),
      },
    },
  }

  const vldator$ = useVuelidate(rules, { resource })

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
    resource,
    vldator$,
    createNewResource,
  }
}
