import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"
import * as api from "../../api/supplierFetcher"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"
import { email, maxLength, required } from "@vuelidate/validators"

export function useSupplierDetail() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const supplier = ref({
    is_active: true,
    description: "",
  })

  // validation
  const rules = {
    supplier: {
      name: { required, maxLength: maxLength(255) },
      description: { maxLength: maxLength(1023) },
      email: { email, required, maxLength: maxLength(255) },
      phone_number: { required, maxLength: maxLength(15) },
      fax: { required, maxLength: maxLength(255) },
      address: { required, maxLength: maxLength(255) },
      categories: { required },
    },
  }
  const supplierValidator$ = useVuelidate(rules, { supplier })

  /**
   * @param payload
   * @returns industry
   */
  const updateSupplier = async (supplierUid, payload) => {
    try {
      const { data } = await api.updateSupplier(supplierUid, payload)
      globalStore.addSuccessToastMessage(t("supplier.messages.updatedSupplier"))
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  /**
   *
   * @param uid
   * @returns {Promise<undefined>}
   */
  async function getDetailSupplier(uid) {
    try {
      const { data } = await api.fetchSupplier(uid)
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : this.$t("activity.errors.listSupplier"))
    }
  }

  return {
    supplier,
    supplierValidator$,
    getDetailSupplier,
    updateSupplier,
  }
}
