import * as api from "../api/assessmentFetcher"
import { useGlobalStore } from "@/stores/global"

export function useBIALog() {
  const globalStore = useGlobalStore()

  /**
   *
   * @param {*} payload
   * @returns
   */
  const getBIALogs = async (uid) => {
    try {
      const { data } = await api.fetBIALogs(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getBIALogs,
  }
}
