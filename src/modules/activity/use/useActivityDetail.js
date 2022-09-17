import * as api from "../api/activityFetcher"
import { useGlobalStore } from "@/stores/global"

export function useActivityDetail() {
  const globalStore = useGlobalStore()

  /**
   *
   * @param {*} payload
   * @returns
   */
  const getActivity = async (uid) => {
    try {
      const { data } = await api.fetchActivity(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getActivity,
  }
}
