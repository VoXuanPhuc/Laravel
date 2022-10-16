import { ref } from "vue"
import * as api from "../../api/dependencyScenarioFetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"

export function useDependencyList() {
  const globalStore = useGlobalStore()

  // Initial data
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)

  const dependencies = ref([])

  const { t } = useI18n()

  /**
   *
   * @returns
   */
  async function getDependencyList() {
    try {
      const { data } = await api.fetchDependencyScenariosList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("resource.errors.listResources"))
    }
  }

  /**
   * Download resources
   * @returns
   */
  async function downloadDependencies(categoryUid) {
    try {
      const { data } = await api.downloadDependencyScenarios(categoryUid)

      if (!data) {
        globalStore.addErrorToastMessage(this.$t("resources.errors.download"))
        return
      }

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement("a")
      link.href = url

      link.setAttribute("download", `Dependency_Scenarios_${Date.now()}.xlsx`)
      document.body.appendChild(link)
      link.click()
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("resource.errors.download"))
      return false
    }
  }

  return {
    getDependencyList,
    downloadDependencies,
    dependencies,
    t,
    totalItems,
    skip,
    limit,
    currentPage,
  }
}
