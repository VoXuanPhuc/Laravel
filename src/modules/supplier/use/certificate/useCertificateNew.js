import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"
import * as api from "../../api/supplierCertificateFetcher"
import { ref } from "vue"

export function useCertificateNew() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const certificate = ref({
    certs: [],
  })

  /**
   * @param payload
   * @returns {Promise<undefined>}
   */
  const uploadCertificate = async (supplierUid, payload) => {
    try {
      const { data } = await api.createCertificate(supplierUid, payload)
      globalStore.addSuccessToastMessage(t("supplier.certificate.uploadSuccess"))
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    certificate,
    uploadCertificate,
  }
}
