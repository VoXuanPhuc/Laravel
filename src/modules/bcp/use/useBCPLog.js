import * as api from "../api/bcpFetcher"
import { useGlobalStore } from "@/stores/global"

export function useBCPLog() {
  const globalStore = useGlobalStore()

  /**
   *
   * @param {*} payload
   * @returns
   */
  const getBCPLogs = async (uid) => {
    try {
      const { data } = await api.fetchBCPLogs(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getBCPLogs,
  }
}
