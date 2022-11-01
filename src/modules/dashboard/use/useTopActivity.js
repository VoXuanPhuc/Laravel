import { useGlobalStore } from "@/stores/global"
import * as api from "../api/topActivityFetcher"

export function useTopActivity() {
  const globalStore = useGlobalStore()

  /**
   *
   * @returns
   */
  const getTopActivities = async () => {
    try {
      const { data } = await api.fetchTopActivities()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getTopActivities,
  }
}
