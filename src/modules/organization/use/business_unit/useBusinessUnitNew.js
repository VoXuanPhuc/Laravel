import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import * as adminApi from "../../api/adminBusinessUnitFetcher"

export const useBusinessUnitNew = () => {
  // Global store
  const globalStore = useGlobalStore()

  const form = ref({
    avatar_color: "#110663",
    is_active: true,
    division: { uid: "" },
  })

  const rules = {
    form: {
      name: { required },
      division: {
        uid: { required },
      },
      description: {},
    },
  }

  const v$ = useVuelidate(rules, { form })

  const createBusinessUnit = async (payload, organizationUid, divisionUid) => {
    try {
      const { data } = await adminApi.createBusinessUnit(payload, organizationUid, divisionUid)

      globalStore.addSuccessToastMessage("Created Business Unit")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  return {
    form,
    v$,
    createBusinessUnit,
  }
}
