import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import * as api from "../api/activityFetcher"
import { useGlobalStore } from "@/stores/global"

export function useActivityRemoteAccessFactors() {
  const globalStore = useGlobalStore()

  const form = ref({
    remote_access_factors: [""],
  })

  const rules = {
    form: {
      remote_access_factors: { required },
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

      globalStore.addSuccessToastMessage("Updated activity, redirect to next step...")
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
