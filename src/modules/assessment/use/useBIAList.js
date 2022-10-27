import { ref } from "vue"
import * as api from "../api/assessmentFetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"
import { downloadFromBlob } from "@/readybc/composables/helpers/downloadHelper"

export function useBIAList() {
  const globalStore = useGlobalStore()

  // Initial data
  const bias = ref([])

  const { t } = useI18n()

  /**
   *
   * @returns
   */
  async function getBIAList(filters) {
    try {
      const { data } = await api.fetchBIAList(filters)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("bia.errors.listBcps"))
    }
  }

  /**
   * Download bcps
   * @returns
   */
  async function downloadBIAs() {
    try {
      const { data } = await api.downloadBIAs()

      debugger
      if (!data) {
        globalStore.addErrorToastMessage(this.$t("bia.errors.download"))
        return
      }

      downloadFromBlob(data, "Business_Impact_Assessments", "xlsx")
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("bia.errors.download"))
      return false
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  async function exportBIARecord(uid) {
    try {
      const { data } = await api.exportBIA(uid)
      debugger
      if (!data) {
        globalStore.addErrorToastMessage(this.$t("bia.errors.download"))
        return
      }

      downloadFromBlob(data, `Business_Impact_Assessment`, "zip")
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("bia.errors.download"))
      return false
    }
  }

  return {
    getBIAList,
    downloadBIAs,
    exportBIARecord,
    bias,
    t,
  }
}
