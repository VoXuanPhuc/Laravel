import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import * as api from "../../api/businessUnitFetcher"

export const useBusinessUnitDetail = () => {
  // Global store
  const globalStore = useGlobalStore()

  const businessUnit = ref({
    avatar_color: "#110663",
    is_active: true,
    division: { uid: "" },
  })

  const rules = {
    businessUnit: {
      name: { required },
      division: {
        uid: { required },
      },
      description: { required },
    },
  }

  const v$ = useVuelidate(rules, { businessUnit })

  /**
   * Get division
   *
   * @param {*} organizationUid
   * @param {*} uid
   * @returns
   */
  const getBusinessUnit = async (divisionUid, uid) => {
    try {
      const { data } = await api.fetchBusinessUnit(divisionUid, uid)

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
  const updateBusinessUnit = async (payload, divisionUid, uid) => {
    try {
      const { data } = await api.updateBusinessUnit(payload, divisionUid, uid)

      globalStore.addSuccessToastMessage("Updated")
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  /**
   *
   * @param {*} organizationUid
   * @param {*} divisionUid
   * @param {*} uid
   * @returns
   */
  const deleteBusinessUnit = async (divisionUid, uid) => {
    try {
      const { data } = await api.deleteBusinessUnit(divisionUid, uid)

      globalStore.addSuccessToastMessage("Deleted")
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  return {
    businessUnit,
    v$,
    getBusinessUnit,
    updateBusinessUnit,
    deleteBusinessUnit,
  }
}