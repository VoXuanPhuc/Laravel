import { ref } from "vue"

// import { useRouter } from "vue-router"
import useVuelidate from "@vuelidate/core"
import { required } from "@vuelidate/validators"

// import { useI18n } from "vue-i18n"
// import { handleErrorForUser } from "../api/handleErrorForUser"

export function useOrganizationCreate() {
  // Initial data
  const form = ref({
    name: "",
    description: "",
    phone: "",
    friendlyUrl: "",
    address: "",
    isActive: true,
    owner: {
      firstName: "",
      lastName: "",
      email: "",
      phone: "",
    },
  })

  // Validation
  const rules = {
    form: {
      name: { required },
      friendlyUrl: {},
      description: {},
      address: {},
      isActive: { required },
      owner: {
        firstName: { required },
        lastName: { required },
        email: { required },
        phone: { required },
      },
    },
  }
  const v$ = useVuelidate(rules, { form })

  return {
    v$,
    form,
  }
}
