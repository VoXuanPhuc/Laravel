import { useGlobalStore } from "@/stores/global"
import * as api from "../api/topBCPFetcher"

export function useTopBCP() {
  const globalStore = useGlobalStore()

  /**
   *
   * @returns
   */
  const getTopBCPs = async () => {
    try {
      const { data } = await api.fetchTopBCPs()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getTopBCPs,
  }
}
