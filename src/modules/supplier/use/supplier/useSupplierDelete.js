import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"
import * as api from "../../api/supplierFetcher"

export function useSupplierDelete() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()

  /**
   * @param uid
   * @returns industry
   */
  const deleteSupplier = async (uid) => {
    try {
      const { data } = await api.deleteSupplier(uid)
      globalStore.addSuccessToastMessage(t("supplier.messages.deleteSuccess"))
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    deleteSupplier,
  }
}
