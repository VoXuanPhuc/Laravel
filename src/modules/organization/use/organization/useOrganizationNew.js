import { ref } from "vue"

// import { useRouter } from "vue-router"
import useVuelidate from "@vuelidate/core"
import { required } from "@vuelidate/validators"
import * as api from "../../api/organizationFetcher"
import { useGlobalStore } from "@/stores/global"

// import { useI18n } from "vue-i18n"
// import { handleErrorForUser } from "../api/handleErrorForUser"

export function useOrganizationCreate() {
  const globalStore = useGlobalStore()

  // Initial data
  const form = ref({
    is_active: true,
    owner: {},
  })

  // Validation
  const rules = {
    form: {
      name: { required },
      friendly_url: {},
      description: {},
      address: {},
      is_active: { required },
      owner: {
        first_name: { required },
        last_name: { required },
        email: { required },
        phone: { required },
      },
      industries: { required },
    },
  }
  const v$ = useVuelidate(rules, { form })

  const createOrganization = async (payload) => {
    try {
      const { data } = await api.createOrganization(payload)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  return {
    v$,
    form,
    createOrganization,
  }
}
