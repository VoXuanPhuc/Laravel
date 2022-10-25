import { ref } from "vue"
import * as api from "../api/bcpFetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"

export function useBCPList() {
  const globalStore = useGlobalStore()

  // Initial data
  const bcps = ref([])

  const { t } = useI18n()

  /**
   *
   * @returns
   */
  async function getBCPList(filters) {
    try {
      const { data } = await api.fetchBCPList(filters)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("resource.errors.listBcps"))
    }
  }

  /**
   * Download bcps
   * @returns
   */
  async function downloadBCPs(categoryUid) {
    try {
      const { data } = await api.downloadBCPs(categoryUid)

      if (!data) {
        globalStore.addErrorToastMessage(this.$t("bcps.errors.download"))
        return
      }

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement("a")
      link.href = url

      link.setAttribute("download", `Business_Continuity_Plans_${Date.now()}.xlsx`)
      document.body.appendChild(link)
      link.click()
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("resource.errors.download"))
      return false
    }
  }

  return {
    getBCPList,
    downloadBCPs,
    bcps,
    t,
  }
}
