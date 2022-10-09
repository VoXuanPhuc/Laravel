import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/supplierCertificateFetcher"

export function useCertificateList() {
  const globalStore = useGlobalStore()

  /**
   * @param supplierUid
   * @returns {Promise<undefined>}
   */
  const getCertificateListBySupplier = async (supplierUid) => {
    try {
      const { data } = await api.fetchCertificateList(supplierUid)
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    getCertificateListBySupplier,
  }
}
