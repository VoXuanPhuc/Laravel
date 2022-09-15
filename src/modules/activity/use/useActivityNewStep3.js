import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required } from "@vuelidate/validators"

export function useActivityNewStep3() {
  const form = ref({
    softwares: [""],
    equipments: [""],

    it: {
      data: "",
      location: "",
    },
  })

  const rules = {
    form: {
      it: {
        data: { required },
        location: { required },
      },
      softwares: { required },
      equipments: { required },
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
