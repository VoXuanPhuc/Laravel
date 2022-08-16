import * as api from "@/readybc/composables/api/apiRoles"
import { useGlobalStore } from "@/stores/global"

/**
 *
 * @returns
 */
export const useRoleList = () => {
  const globalStore = useGlobalStore()

  /**
   *
   * @returns
   */
  async function getRoles() {
    try {
      const { data } = await api.fetchRoles()

      return data
    } catch (error) {
      return error
    }
  }

  /**
   *
   * @param {*} uid
   * @returns
   */
  async function deleteRole(uid) {
    try {
      const { data } = await api.deleteRole(uid)

      if (!data || data.error) {
        throw new Error(data.error)
      }

      globalStore.addToastMessage({ type: "success", content: "Added permission successfully" })
      return data
    } catch (error) {
      globalStore.addToastMessage({ type: "error", content: error?.message })
      return error
    }
  }

  return {
    getRoles,
    deleteRole,
  }
}
