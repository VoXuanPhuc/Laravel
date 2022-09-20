import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"
import * as api from "../../api/industryFetcher"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"
import { maxLength, required } from "@vuelidate/validators"

export function useIndustryCreate() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const industry = ref({})
  // error code for unique name
  const NAME_UNIQUE = "NAME_UNIQUE"

  // validation
  const rules = {
    industry: {
      name: { required, maxLength: maxLength(255) },
      description: { maxLength: maxLength(255) },
    },
  }
  const v$ = useVuelidate(rules, { industry })

  /**
   * @param payload
   * @returns industry
   */
  const createIndustry = async (payload) => {
    try {
      const { data } = await api.createIndustry(payload)
      globalStore.addSuccessToastMessage(t("industry.created"))
      return data
    } catch (error) {
      // return error if name has been used
      if (error.message === NAME_UNIQUE) {
        return error
      }
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    industry,
    v$,
    createIndustry,
  }
}
