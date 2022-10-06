import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/divisionFetcher"

export const useDivisionDetail = () => {
  // Global store
  const globalStore = useGlobalStore()

  const division = ref({
    is_active: true,
  })

  const rules = {
    division: {
      name: { required },
      description: { required },
    },
  }

  const v$ = useVuelidate(rules, { division })

  /**
   * Get division
   *
   * @param {*} uid
   * @returns
   */
  const getDivision = async (uid) => {
    try {
      const { data } = await api.fetchDivision(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  /**
   *
   * @param {*} payload
   * @param {*} organizationUid
   * @param {*} uid
   * @returns
   */
  const updateDivision = async (payload, uid) => {
    try {
      const { data } = await api.updateDivision(payload, uid)

      globalStore.addSuccessToastMessage("Updated")
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  const deleteDivision = async (uid) => {
    try {
      const { data } = await api.deleteDivision(uid)

      globalStore.addSuccessToastMessage("Deleted")
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  return {
    division,
    v$,
    getDivision,
    updateDivision,
    deleteDivision,
  }
}
