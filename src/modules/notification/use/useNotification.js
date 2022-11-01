import { useGlobalStore } from "@/stores/global"
import * as api from "../api/notificationFetcher"

export function useNotification() {
  const globalStore = useGlobalStore()

  /**
   *
   * @returns
   */
  const readNotification = async (uid) => {
    try {
      const { data } = await api.markNotificationAsRead(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    readNotification,
  }
}
