import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/dependencyFactorFetcher"
import { ref } from "vue"

export function useDependencyFactor() {
  const globalStore = useGlobalStore()
  const dependencyFactors = ref([])

  /**
   *
   * @returns
   */
  async function getDependencyFactors() {
    try {
      const { data } = await api.fetchDepedencyFactors()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.errors.listCategory"))
    }
  }

  return {
    getDependencyFactors,
    dependencyFactors,
  }
}
