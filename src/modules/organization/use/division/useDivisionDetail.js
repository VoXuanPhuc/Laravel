import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import * as adminApi from "../../api/adminDivisionFetcher"

export const useDivisionDetail = () => {
  // Global store
  const globalStore = useGlobalStore()

  const division = ref({
    is_active: true,
  })

  const rules = {
    division: {
      name: { required },
      description: {},
    },
  }

  const v$ = useVuelidate(rules, { division })

  /**
   * Get division
   *
   * @param {*} organizationUid
   * @param {*} uid
   * @returns
   */
  const getDivision = async (organizationUid, uid) => {
    try {
      const { data } = await adminApi.fetchDivision(organizationUid, uid)

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
  const updateDivision = async (payload, organizationUid, uid) => {
    try {
      const { data } = await adminApi.updateDivision(payload, organizationUid, uid)

      globalStore.addSuccessToastMessage("Updated")
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  const deleteDivision = async (organizationUid, uid) => {
    try {
      const { data } = await adminApi.deleteDivision(organizationUid, uid)

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
