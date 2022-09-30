import { ref } from "vue"
import * as api from "../../api/resourceFetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"

export function useResourceList() {
  const globalStore = useGlobalStore()

  // Initial data
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)

  const resources = ref([])

  const { t } = useI18n()

  /**
   *
   * @returns
   */
  async function getResourceList() {
    try {
      const { data } = await api.fetchResourceList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("resource.errors.listResources"))
    }
  }

  /**
   * Download resources
   * @returns
   */
  async function downloadResources(categoryUid) {
    try {
      const { data } = await api.downloadResources(categoryUid)

      if (!data) {
        globalStore.addErrorToastMessage(this.$t("resources.errors.download"))
        return
      }

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement("a")
      link.href = url

      link.setAttribute("download", `Resources_${Date.now()}.xlsx`)
      document.body.appendChild(link)
      link.click()
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("resource.errors.download"))
      return false
    }
  }

  return {
    getResourceList,
    downloadResources,
    resources,
    t,
    totalItems,
    skip,
    limit,
    currentPage,
  }
}
