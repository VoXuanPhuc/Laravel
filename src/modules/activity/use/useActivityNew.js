import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required, numeric, minValue, helpers, minLength } from "@vuelidate/validators"

export function useActivityNew() {
  const form = ref({
    roles: [""],

    alternative_roles: [""],

    utilities: [""],

    min_people: 1,
    is_remote: true,
  })

  const rules = {
    form: {
      name: { required },
      min_people: {
        required,
        numeric,
        minValue: minValue(1),
      },
      is_remote: { required },
      roles: {
        required,
        $each: helpers.forEach({
          minLength: minLength(1),
        }),
      },
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
