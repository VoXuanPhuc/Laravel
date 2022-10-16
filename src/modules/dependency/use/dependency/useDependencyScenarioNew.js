import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import * as api from "../../api/dependencyScenarioFetcher"
import { useGlobalStore } from "@/stores/global"

export function useDependencyScenarioNew() {
  const globalStore = useGlobalStore()

  const dependencyScenario = ref({
    targets: [],
    upstream: [],
    downstream: [],
  })

  const rules = {
    dependencyScenario: {
      name: { required },
      description: {},
      targets: { required },
      upstream: {},
      downstream: {},
    },
  }

  const valdator$ = useVuelidate(rules, { dependencyScenario })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createNewDependencyScenario = async (payload) => {
    try {
      const { data } = await api.createDependencySenario(payload)
      globalStore.addSuccessToastMessage("Created new dependency scenario, redirect to dependency scenario list...")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    dependencyScenario,
    valdator$,
    createNewDependencyScenario,
  }
}
