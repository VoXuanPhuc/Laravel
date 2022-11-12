import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { maxLength, required } from "@vuelidate/validators"
import * as api from "../api/activityFetcher"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"

export function useActivityTolerant() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const mtdpTimeOptions = ref([])

  const form = ref({
    tolerable_period_disruptions: {},
  })

  const rules = {
    form: {
      tolerable_period_disruptions: {
        uid: { required },
      },
      dependent_time: { maxLength: maxLength(500) },
      reason_choose_dependent_time: { maxLength: maxLength(500) },
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @param {*} activityUid
   * @returns
   */
  const getMTDPTimeOptions = async () => {
    try {
      const { data } = await api.getMTDPTimePeriodOptions()

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
  const updateActivityMTDP = async (payload, activityUid) => {
    try {
      const { data } = await api.updateActivityMTDP(payload, activityUid)

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
    form,
    v$,
    mtdpTimeOptions,
    getMTDPTimeOptions,
    updateActivityMTDP,
  }
}
