import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"
import * as api from "../../api/industryFetcher"
import { ref } from "vue"
import useVuelidate from "@vuelidate/core"
import { maxLength, required } from "@vuelidate/validators"

export function useIndustryDetail() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  const industry = ref({})
  // error code for unique name
  const NAME_UNIQUE = "NAME_UNIQUE"

  // validate industry
  const rules = {
    industry: {
      name: { required, maxLength: maxLength(255) },
      description: { maxLength: maxLength(255) },
    },
  }
  const v$ = useVuelidate(rules, { industry })

  /**
   * @param industryUid
   * @returns industries
   */
  const getIndustry = async (industryUid) => {
    try {
      const { data } = await api.fetchIndustryDetail(industryUid)
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  /**
   * @param industryUid
   * @param payload
   * @returns industry
   */
  const updateIndustry = async (industryUid, payload) => {
    try {
      const { data } = await api.updateIndustry(industryUid, payload)
      globalStore.addSuccessToastMessage(t("industry.updated"))
      return data
    } catch (error) {
      // return error if name has been used
      if (error.message === NAME_UNIQUE) {
        return error
      }
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }
  const deleteIndustry = async (industryId) => {
    try {
      const { data } = await api.deleteIndustry(industryId)
      globalStore.addSuccessToastMessage(t("industry.deleted"))
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    industry,
    v$,
    getIndustry,
    updateIndustry,
    deleteIndustry,
  }
}
