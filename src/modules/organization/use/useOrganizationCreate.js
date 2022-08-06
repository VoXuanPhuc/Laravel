import { ref } from "vue"

// import { useRouter } from "vue-router"
import useVuelidate from "@vuelidate/core"
import { required } from "@vuelidate/validators"

// import { useI18n } from "vue-i18n"
// import { handleErrorForUser } from "../api/handleErrorForUser"

export function useOrganizationCreate() {
  // Initial data
  const nameFormat = ref("")
  const isActive = ref(true)

  // Validation
  const rules = {
    nameFormat: { required },
    isActive: { required },
  }
  const v = useVuelidate(rules, {
    nameFormat,
    isActive,
  })

  return {
    nameFormat,
    isActive,
    v,
  }
}
