import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"
import * as api from "../../api/supplierFetcher"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"
import { email, maxLength, required } from "@vuelidate/validators"

export function useSupplierNew() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const supplier = ref({
    is_active: true,
    description: "",
    certs: [],
  })

  // validation
  const rules = {
    supplier: {
      name: { required, maxLength: maxLength(255) },
      description: { maxLength: maxLength(1023) },
      email: { email, required, maxLength: maxLength(255) },
      phone_number: { required, maxLength: maxLength(15) },
      address: { required, maxLength: maxLength(255) },
      categories: { required },
    },
  }
  const supplierValidator$ = useVuelidate(rules, { supplier })

  /**
   * @param payload
   * @returns industry
   */
  const createSupplier = async (payload) => {
    try {
      const { data } = await api.createSupplier(payload)
      globalStore.addSuccessToastMessage(t("supplier.messages.createSuccess"))
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    supplier,
    supplierValidator$,
    createSupplier,
  }
}
