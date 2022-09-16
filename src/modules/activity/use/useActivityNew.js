import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { required, numeric, minValue, helpers } from "@vuelidate/validators"
import * as api from "../api/activityFetcher"
import { useGlobalStore } from "@/stores/global"

export function useActivityNew() {
  const globalStore = useGlobalStore()

  const form = ref({
    division: {},
    business_unit: {},
    roles: [{ uid: "" }],
    alternative_roles: [{ uid: "" }],
    utilities: [{ uid: "" }],
    min_people: 1,
    is_remote: true,
  })

  const rules = {
    form: {
      name: { required },
      description: {},
      min_people: {
        required,
        numeric,
        minValueLength: minValue(1),
      },
      is_remote: { required },
      roles: {
        required,
        $each: helpers.forEach({
          uid: { required },
        }),
      },
      alternative_roles: {},
      utilities: {
        required,
        $each: helpers.forEach({
          uid: { required },
        }),
      },
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createNewActivity = async (payload) => {
    debugger
    try {
      const { data } = await api.createNewActivity(payload)
      globalStore.addSuccessToastMessage("Created new activity, redirect to next step...")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    form,
    v$,
    createNewActivity,
  }
}
