import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import * as api from "../api/activityFetcher"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"

export function useDependenciesAndSuppliers() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()

  const form = ref({
    dependency_scenarios: [],
    suppliers: [],
  })

  const rules = {
    form: {
      dependency_scenarios: {},
      suppliers: {},
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @param {*} activityUid
   * @returns
   */
  const updateDependenciesAndSuppliers = async (payload, activityUid) => {
    try {
      const { data } = await api.updateDependencyAndSupplier(payload, activityUid)

      if (!data) {
        globalStore.addErrorToastMessage(t("activity.errors.updateActivity"))
      } else {
        globalStore.addSuccessToastMessage(t("activity.messages.updatedActivity"))
      }

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    form,
    v$,
    updateDependenciesAndSuppliers,
  }
}
