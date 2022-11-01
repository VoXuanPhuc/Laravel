import { useGlobalStore } from "@/stores/global"
import * as api from "../api/topBIAFetcher"

export function useTopBIA() {
  const globalStore = useGlobalStore()

  /**
   *
   * @returns
   */
  const getTopBIAs = async () => {
    try {
      const { data } = await api.fetchTopBIAs()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getTopBIAs,
  }
}
