import { useGlobalStore } from "@/stores/global"
import * as api from "@/modules/notification/api/eventNofiticationFetcher"
import { ref } from "vue"

export function useEventNotificationList() {
  const globalStore = useGlobalStore()

  const eventNotificationList = ref([])

  /**
   *
   * @param {*} filters
   * @returns
   */
  const getNotificationTemplateList = async (filters) => {
    try {
      const { data } = await api.fetchEventNotificationList(filters)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("notification.errors.listTemplates"))
    }
  }

  return {
    eventNotificationList,
    getNotificationTemplateList,
  }
}
