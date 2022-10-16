import * as api from "../../api/dependencyScenarioFetcher"
import { useGlobalStore } from "@/stores/global"
import { required } from "@vuelidate/validators"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"

export function useDependencyScenarioDetail() {
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
  const getDependencyScenario = async (uid) => {
    try {
      const { data } = await api.fetchDependencyScenario(uid)

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
  const updateDependencyScenario = async (payload, uid) => {
    try {
      const { data } = await api.updateDependencyScenario(payload, uid)
      globalStore.addSuccessToastMessage("Updated dependency scenario")

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
  const deleteDependencyScenario = async (uid) => {
    try {
      const response = await api.deleteDependencyScenario(uid)

      return response.status === 200
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }
  return {
    getDependencyScenario,
    updateDependencyScenario,
    deleteDependencyScenario,
    dependencyScenario,
    valdator$,
  }
}
