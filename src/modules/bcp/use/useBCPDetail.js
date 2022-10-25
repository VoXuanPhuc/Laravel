import * as api from "../api/bcpFetcher"
import * as apiFile from "@/readybc/composables/api/apiUploadFile"
import { useGlobalStore } from "@/stores/global"
import { required } from "@vuelidate/validators"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"

export function useBCPDetail() {
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

  const v$ = useVuelidate(rules, { bcp })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const getBCP = async (uid) => {
    try {
      const { data } = await api.fetchBCP(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} payload
   * @returns
   */
  const updateBCP = async (payload, uid) => {
    try {
      const { data } = await api.updateBCP(payload, uid)
      globalStore.addSuccessToastMessage("Updated BCP")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  const deleteBCP = async (uid) => {
    try {
      const response = await api.deleteBCP(uid)

      return response.status === 200
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param {*} uid
   */
  const removeReportFile = async (uid) => {
    try {
      const response = await apiFile.apiDeleteFile(uid)

      return response.status === 200
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  return {
    getBCP,
    updateBCP,
    deleteBCP,
    removeReportFile,
    bcp,
    v$,
  }
}
