import { useGlobalStore } from "@/stores/global"
import { ref } from "vue"
import * as api from "../api/notificationFetcher"

export function useNotification() {
  const globalStore = useGlobalStore()
  const eventNotificationLogs = ref()

  /**
   *
   * @returns
   */
  const getNotificationLogs = async () => {
    try {
      const { data } = await api.fetchNotificationLogs()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }
  /**
   *
   * @returns
   */
  const getNotificationLogDetail = async (uid) => {
    try {
      const { data } = await api.fetchNotificationLogDetail(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

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
    eventNotificationLogs,
    getNotificationLogs,
    getNotificationLogDetail,
    readNotification,
  }
}
