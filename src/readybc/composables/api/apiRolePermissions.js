import fetcher from "@/modules/core/api/fetcher"

/**
 * Role add permission
 * @returns
 */
export const roleAddPermission = async (roleUid, payload) => {
  return fetcher.put(`/identity/api/v1/roles/${roleUid}/permissions`, payload)
}

/**
 * Role remove permission
 * @returns
 */
export const roleRemovePermission = async (roleUid, permissionUid) => {
  return fetcher.delete(`/identity/api/v1/roles/${roleUid}/permissions/${permissionUid}`)
}
