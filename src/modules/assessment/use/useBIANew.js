import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import * as api from "../api/assessmentFetcher"
import { useGlobalStore } from "@/stores/global"

export function useBIANew() {
  const globalStore = useGlobalStore()

  const bia = ref({
    reports: [],
  })

  const rules = {
    bia: {
      name: { required },
      due_date: { required },
      statusObj: { required },
      reports: {},
    },
  }

  const v$ = useVuelidate(rules, { bia })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createNewBIA = async (payload) => {
    try {
      const { data } = await api.createNewBIA(payload)
      globalStore.addSuccessToastMessage("Created new BIA, redirect to BIA list...")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    bia,
    v$,
    createNewBIA,
  }
}
