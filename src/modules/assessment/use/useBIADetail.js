import * as api from "../api/assessmentFetcher"
import * as apiFile from "@/readybc/composables/api/apiUploadFile"
import { useGlobalStore } from "@/stores/global"
import { required } from "@vuelidate/validators"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"

export function useBIADetail() {
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
  const getBIA = async (uid) => {
    try {
      const { data } = await api.fetchBIA(uid)

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
  const updateBIA = async (payload, uid) => {
    try {
      const { data } = await api.updateBIA(payload, uid)
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
  const deleteBIA = async (uid) => {
    try {
      const response = await api.deleteBIA(uid)

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
    getBIA,
    updateBIA,
    deleteBIA,
    removeReportFile,
    bia,
    v$,
  }
}
