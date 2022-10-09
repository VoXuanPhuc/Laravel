import { ref } from "vue"
import * as api from "../../api/supplierFetcher"
import { useI18n } from "vue-i18n"
import { useGlobalStore } from "@/stores/global"

export function useSupplierList() {
  const globalStore = useGlobalStore()

  // Initial data
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)

  const suppliers = ref([])

  const { t } = useI18n()

  /**
   *
   * @returns
   */
  async function getSuppliers() {
    try {
      const { data } = await api.fetchSupplierList()
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("supplier.errors.listSupplier"))
    }
  }

  async function downloadSuppliers() {
    try {
      const { data } = await api.downloadSuppliers()

      if (!data) {
        globalStore.addErrorToastMessage(this.$t("supplier.errors.download"))
        return
      }

      const url = window.URL.createObjectURL(new Blob([data]))
      const link = document.createElement("a")
      link.href = url

      link.setAttribute("download", `Suppliers_${Date.now()}.xlsx`)
      document.body.appendChild(link)
      link.click()
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("supplier.errors.download"))
    }
  }

  return {
    downloadSuppliers,
    globalStore,
    getSuppliers,
    suppliers,
    t,
    totalItems,
    skip,
    limit,
    currentPage,
  }
}
