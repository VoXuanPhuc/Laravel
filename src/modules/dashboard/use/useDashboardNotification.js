import { useGlobalStore } from "@/stores/global"
import * as api from "../api/dashboardNotificationFetcher"

export function useDashboardNotification() {
  const globalStore = useGlobalStore()

  /**
   *
   * @returns
   */
  const getDashboardNotifications = async () => {
    try {
      const { data } = await api.fetchDashboardNotifications()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getDashboardNotifications,
  }
}
