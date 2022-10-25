import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import * as api from "../api/bcpFetcher"
import { useGlobalStore } from "@/stores/global"

export function useBCPNew() {
  const globalStore = useGlobalStore()

  const bcp = ref({
    reports: [],
  })

  const rules = {
    bcp: {
      name: { required },
      due_date: { required },
      statusObj: { required },
      reports: {},
    },
  }

  const vldator$ = useVuelidate(rules, { bcp })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createNewBCP = async (payload) => {
    try {
      const { data } = await api.createNewBCP(payload)
      globalStore.addSuccessToastMessage("Created new BCP, redirect to BCP list...")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    bcp,
    vldator$,
    createNewBCP,
  }
}
