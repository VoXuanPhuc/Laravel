import { useGlobalStore } from "@/stores/global"
import * as api from "../api/divisionFetcher"
import { ref } from "vue"

export function useDivisionList() {
  const globalStore = useGlobalStore()
  const divisions = ref([])

  /**
   *
   * @returns
   */
  async function getDivisionList() {
    try {
      const { data } = await api.fetchDivisionList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("activity.errors.listDivision"))
    }
  }

  return {
    getDivisionList,
    divisions,
  }
}
