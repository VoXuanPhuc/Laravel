import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import isEmpty from "lodash.isempty"
import * as api from "../../api/divisionFetcher"

export const useDivisionNew = () => {
  // Global store
  const globalStore = useGlobalStore()

  const form = ref({
    avatar_color: "#110663",
    is_active: true,
  })

  const rules = {
    form: {
      name: { required },
      description: {},
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} name
   * @param {*} color
   * @returns
   */
  const generateAvatar = (name, color) => {
    // Only generate if the name with 2 letters
    if (isEmpty(name) || name.length < 2 || isEmpty(color)) {
      return ""
    }

    color = color.replace("#", "")

    const avartarLetter = name.substr(0, 2)

    return `https://ui-avatars.com/api/?name=${avartarLetter}&background=${color}&color=fff`
  }

  /**
   *
   * @param {*} payload
   * @returns
   */
  const createDivision = async (payload) => {
    try {
      const { data } = await api.createDivision(payload)

      globalStore.addSuccessToastMessage("Created Division")

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  return {
    form,
    v$,
    createDivision,
    generateAvatar,
  }
}
