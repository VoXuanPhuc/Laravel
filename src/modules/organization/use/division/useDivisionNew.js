import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import _ from "lodash"
import * as adminApi from "../../api/adminDivisionFetcher"

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
      description: { required },
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
    if (_.isEmpty(name) || name.length < 2 || _.isEmpty(color)) {
      return ""
    }

    color = color.replace("#", "")

    const avartarLetter = name.substr(0, 2)

    return `https://ui-avatars.com/api/?name=${avartarLetter}&background=${color}&color=fff`
  }

  const createDivision = async (organizationUid, payload) => {
    try {
      const { data } = await adminApi.createDivision(organizationUid, payload)

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
