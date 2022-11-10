import { useGlobalStore } from "@/stores/global"
import * as api from "@/modules/notification/api/templateFetcher"
import { ref } from "vue"
import { useI18n } from "vue-i18n"

export function useManagedTemplateDetail() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()

  const templateList = ref([])

  /**
   *
   * @returns
   */
  const getManagedTemplateDetail = async (uid) => {
    try {
      const { data } = await api.fetchTemplateDetail(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.templateDetail"))
    }
  }

  /**
   *
   * @returns
   */
  const updateManagedTemplate = async (payload, uid) => {
    try {
      const { data } = await api.updateTemplate(payload, uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.listTemplates"))
    }
  }

  return {
    templateList,

    getManagedTemplateDetail,
    updateManagedTemplate,
  }
}
