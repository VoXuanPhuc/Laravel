import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/ownerFetcher"
import { ref } from "vue"

export function useOwnerList() {
  const globalStore = useGlobalStore()
  const owners = ref([])

  /**
   *
   * @returns
   */
  async function getOwnerList() {
    try {
      const { data } = await api.fetchOwnerList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.errors.listOwner"))
    }
  }

  /**
   *
   * @returns
   */
  async function getOwnerListAll() {
    try {
      const { data } = await api.fetchOwnerListAll()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.errors.listOwner"))
    }
  }

  return {
    getOwnerList,
    getOwnerListAll,
    owners,
  }
}
