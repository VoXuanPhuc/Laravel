import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/supplierCertificateFetcher"

export function useCertificateDelete() {
  const globalStore = useGlobalStore()

  /**
   * @param supplierUid
   * @returns {Promise<undefined>}
   */
  const deleteCertificate = async (supplierUid, certificateUid) => {
    try {
      const { data } = await api.deleteCertificate(supplierUid, certificateUid)
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    deleteCertificate,
  }
}
