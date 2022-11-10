import { useGlobalStore } from "@/stores/global"
import * as api from "@/modules/notification/api/templateFetcher"
import { ref } from "vue"
import { useI18n } from "vue-i18n"

export function useManagedTemplateList() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()

  const templateList = ref([])

  /**
   *
   * @param {*} filters
   * @returns
   */
  const getManagedTemplateList = async (filters) => {
    try {
      const { data } = await api.fetchTemplateList(filters)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.listTemplates"))
    }
  }

  /**
   *
   * @param {*} filters
   * @returns
   */
  const getManagedTemplateListAll = async (filters) => {
    try {
      const { data } = await api.fetchTemplateListAll(filters)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.listTemplates"))
    }
  }

  return {
    templateList,
    getManagedTemplateList,
    getManagedTemplateListAll,
  }
}
