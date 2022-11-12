import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
// import { helpers, required } from "@vuelidate/validators"
import * as api from "../api/activityFetcher"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"

export function useActivityRemoteAccessFactors() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()

  const form = ref({
    remote_access_factors: [{ uid: "" }],
  })

  const rules = {
    form: {
      remote_access_factors: {
        // $each: helpers.forEach({
        //   uid: { required },
        // }),
      },
      on_site_requires: {},
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @param {*} activityUid
   */
  const updateActivityRemoteAccess = async (payload, activityUid) => {
    try {
      const { data } = await api.updateActivityRemoteAccess(payload, activityUid)

      globalStore.addSuccessToastMessage(t("activity.messages.toNextStep"))
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    form,
    v$,
    updateActivityRemoteAccess,
  }
}
