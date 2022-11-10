import { useGlobalStore } from "@/stores/global"
import * as api from "@/modules/notification/api/templateFetcher"
import { ref } from "vue"
import { useI18n } from "vue-i18n"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"

export function useManagedTemplateNew() {
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
   * Create Template
   * @returns
   */
  const createManagedTemplate = async (payload) => {
    try {
      const { data } = await api.createTemplate(payload)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.listTemplates"))
    }
  }

  return {
    managedTemplate,
    v$,
    createManagedTemplate,
  }
}
