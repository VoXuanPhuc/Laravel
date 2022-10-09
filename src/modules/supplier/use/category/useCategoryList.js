import * as api from "../../api/supplierCategoryFetcher"
import { ref } from "vue"

export function useCategoryList() {
  const categories = ref([])

  const getSupplierCategories = async () => {
    try {
      const { data } = await api.fetchCategoryList()
      return data
    } catch (error) {
      return error
    }
  }

  return {
    categories,
    getSupplierCategories,
  }
}
