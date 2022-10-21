import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
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
      status: {},
      category: {},
      owners: {},
    },
  }

  const vldator$ = useVuelidate(rules, { resource })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createNewResource = async (payload) => {
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
