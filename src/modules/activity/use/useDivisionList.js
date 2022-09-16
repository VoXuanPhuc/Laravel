import { useGlobalStore } from "@/stores/global"
import * as api from "../api/divisionFetcher"
import { ref } from "vue"

export function useDivisionList() {
  const globalStore = useGlobalStore()
  const divisions = ref([])

  async function fetchDivisionList() {
    try {
      const { data } = await api.fetchDivisionList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("activity.error.listDivision"))
    }
  }

  return {
    fetchDivisionList,
    divisions,
  }
}
