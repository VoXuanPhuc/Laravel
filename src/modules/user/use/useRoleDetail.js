import * as api from "@/readybc/composables/api/apiRoles"
import * as apiRolePermissions from "@/readybc/composables/api/apiRolePermissions"
import { useGlobalStore } from "@/stores/global"

export const useRoleDetail = () => {
  const globalStore = useGlobalStore()

  /**
   *
   * @param {*} uid
   * @returns
   */
  const getRoleDetail = async (uid) => {
    try {
      const { data } = await api.fetchRoleDetail(uid)

      return data
    } catch (error) {
      return error
    }
  }

  /**
   *
   * @param {*} roleUid
   * @param {*} permissionUid
   */
  const roleAddPermission = async (roleUid, permissionUid) => {
    console.log(roleUid, permissionUid)
    const payload = {
      permission_uid: permissionUid,
    }
    try {
      const { data } = await apiRolePermissions.roleAddPermission(roleUid, payload)

      if (!data || data.error) {
        throw new Error(data.error)
      }

      globalStore.addSuccessToastMessage("Added permission successfully")

      return true
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  /**
   *
   * @param {*} roleUid
   * @param {*} permissionUid
   */
  const roleRemovePermission = async (roleUid, permissionUid) => {
    try {
      const { data } = await apiRolePermissions.roleRemovePermission(roleUid, permissionUid)
      if (!data || data.error) {
        throw new Error(data.error)
      }

      globalStore.addSuccessToastMessage("Remove permission success")
      return true
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  return {
    getRoleDetail,
    roleAddPermission,
    roleRemovePermission,
  }
}
