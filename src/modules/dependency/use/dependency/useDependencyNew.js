import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required, helpers } from "@vuelidate/validators"
import * as api from "../../api/resourceFetcher"
import { useGlobalStore } from "@/stores/global"

export function useDependencyNew() {
  const globalStore = useGlobalStore()

  const dependency = ref({
    targetDependencies: [
      { uid: "", type: "" },
      { uid: "", type: "" },
    ],
    upstreamDependencies: [
      { uid: "", type: "" },
      { uid: "", type: "" },
    ],
    downstreamDependencies: [
      { uid: "", type: "" },
      { uid: "", type: "" },
    ],
  })

  const rules = {
    dependency: {
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

  const valdator$ = useVuelidate(rules, { dependency })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createNewDependency = async (payload) => {
    try {
      const { data } = await api.createNewResource(payload)
      globalStore.addSuccessToastMessage("Created new resource, redirect to resource list...")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    dependency,
    valdator$,
    createNewDependency,
  }
}
