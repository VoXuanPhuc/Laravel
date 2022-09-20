import * as api from "../../api/industryFetcher"
import { useGlobalStore } from "@/stores/global"
import { ref } from "vue"
import { useI18n } from "vue-i18n"

export function useIndustryList() {
  const globalStore = useGlobalStore()
  // Initial data
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)
  const { t } = useI18n()

  /**
   * @returns industries
   */
  const getIndustryList = async () => {
    try {
      const { data } = await api.fetchIndustryList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }
  return {
    getIndustryList,
    totalItems,
    skip,
    limit,
    currentPage,
    t,
  }
}
