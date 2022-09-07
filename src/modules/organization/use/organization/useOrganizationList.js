import { ref } from "vue"
import { useI18n } from "vue-i18n"
import * as api from "../../api/organizationFetcher"
import { useGlobalStore } from "@/stores/global"

export function useOrganizationList() {
  const globalStore = useGlobalStore()
  // Initial data
  const searchQuery = ref("")
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)
  const { t } = useI18n()

  /**
   *
   * @returns
   */
  const getOrganizationList = async () => {
    try {
      const { data } = await api.fetchOrganizationList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  return {
    searchQuery,
    getOrganizationList,
    totalItems,
    skip,
    limit,
    currentPage,
    t,
  }
}
