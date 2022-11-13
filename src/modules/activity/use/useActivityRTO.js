import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import * as api from "../api/activityRTOFetcher"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"

export function useActivityRTO() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const recoveryTimeOptions = ref([])
  const disruptionScenarios = ref([])

  const EW_NONE_IDENTIFIED = 0
  const EW_FREE_TEXT = 1

  const existingWorkarounds = [
    {
      name: "None identified",
      value: EW_NONE_IDENTIFIED,
    },
    {
      name: "Free Text field",
      value: EW_FREE_TEXT,
    },
  ]

  const form = ref({
    is_rto_tested: false,
    disruption_scenarios: [],
  })

  const rules = {
    form: {
      recoveryTime: { required },
      is_rto_tested: { required },
      disruption_scenarios: { required },
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @param {*} activityUid
   * @returns
   */
  const getRecoveryTimeOptions = async () => {
    try {
      const { data } = await api.fetchRecoveryTimeOptions()

      if (!data) {
        globalStore.addErrorToastMessage(t("activity.errors.getTimeOptions"))
      }

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} payload
   * @param {*} activityUid
   * @returns
   */
  const getDisruptionScenarios = async () => {
    try {
      const { data } = await api.fetchDisruptionScenarios()

      if (!data) {
        globalStore.addErrorToastMessage(t("activity.errors.getDisruptionOptions"))
      }

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} payload
   * @param {*} activityUid
   * @returns
   */
  const updateActivityRTO = async (payload, activityUid) => {
    try {
      const { data } = await api.updateActivityRTO(payload, activityUid)

      if (!data) {
        globalStore.addErrorToastMessage(t("activity.errors.updateActivity"))
      } else {
        globalStore.addSuccessToastMessage(t("activity.messages.toNextStep"))
      }

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    EW_NONE_IDENTIFIED,
    EW_FREE_TEXT,
    form,
    v$,
    existingWorkarounds,
    recoveryTimeOptions,
    disruptionScenarios,
    getRecoveryTimeOptions,
    getDisruptionScenarios,
    updateActivityRTO,
  }
}
