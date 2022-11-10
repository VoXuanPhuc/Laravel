import { useGlobalStore } from "@/stores/global"
import * as api from "@/modules/notification/api/templateFetcher"
import { ref } from "vue"
import { useI18n } from "vue-i18n"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"

export function useManagedTemplateDetail() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()

  const managedTemplate = ref({})

  const rules = {
    managedTemplate: {
      name: { required },
      title: { required },
      description: { required },
      data: { required },
    },
  }

  const v$ = useVuelidate(rules, { managedTemplate })

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

      if (data) {
        globalStore.addSuccessToastMessage("Updated")
      }

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.update"))
    }
  }

  return {
    managedTemplate,
    v$,
    getManagedTemplateDetail,
    updateManagedTemplate,
  }
}
