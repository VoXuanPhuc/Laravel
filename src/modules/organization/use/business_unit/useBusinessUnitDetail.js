import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import * as adminApi from "../../api/adminBusinessUnitFetcher"

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
      description: {},
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
  const getBusinessUnit = async (organizationUid, divisionUid, uid) => {
    try {
      const { data } = await adminApi.fetchBusinessUnit(organizationUid, divisionUid, uid)

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
  const updateBusinessUnit = async (payload, organizationUid, divisionUid, uid) => {
    try {
      const { data } = await adminApi.updateBusinessUnit(payload, organizationUid, divisionUid, uid)

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
  const deleteBusinessUnit = async (organizationUid, divisionUid, uid) => {
    try {
      const { data } = await adminApi.deleteBusinessUnit(organizationUid, divisionUid, uid)

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
