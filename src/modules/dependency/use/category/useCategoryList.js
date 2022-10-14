import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/categoryFetcher"
import { ref } from "vue"

export function useCategoryList() {
  const globalStore = useGlobalStore()
  const categories = ref([])

  /**
   *
   * @returns
   */
  async function getResourceCategoryList() {
    try {
      const { data } = await api.fetchResourceCategoryList()

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error.message : this.$t("resource.errors.listCategory"))
    }
  }

  return {
    getResourceCategoryList,
    categories,
  }
}
