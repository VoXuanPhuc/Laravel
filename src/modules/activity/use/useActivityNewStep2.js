import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required } from "@vuelidate/validators"

export function useActivityNewStep2() {
  const form = ref({
    remote_access_factors: [""],
  })

  const rules = {
    form: {
      remote_access_factors: { required },
    },
  }

  const v$ = useVuelidate(rules, { form })

  const createNewActivity = () => {}

  return {
    form,
    v$,
    createNewActivity,
  }
}
