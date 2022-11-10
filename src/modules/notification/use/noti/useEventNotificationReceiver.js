import * as api from "@/modules/user/api/userFetcher"
import { useGlobalStore } from "@/stores/global"
import { ref } from "vue"
import { useI18n } from "vue-i18n"

export function useEventNotifiactionReceiver() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const receivers = ref([])

  /**
   *
   */
  const getReceivers = async () => {
    try {
      const { data } = await api.getUserList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.fetchReceivers"))
    }
  }

  return {
    receivers,
    getReceivers,
  }
}
