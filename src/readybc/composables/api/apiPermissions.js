import fetcher from "@/modules/core/api/fetcher"

/**
 * Get permissions
 * @returns
 */
export const fetchPermissions = async () => {
  return fetcher.get("/identity/api/v1/permissions")
}

/**
 * Get permissions
 * @returns
 */
export const fetchPermissionsWithGroup = async () => {
  return fetcher.get("/identity/api/v1/permissions-by-group")
}

/**
 * Update permission
 * @param {*} id
 * @param {*} payload
 * @returns
 */
export const updatePermission = async (id, payload) => {
  return fetcher.post(`/identity/api/v1/permissions/${id}`, payload)
}

/**
 *
 * @param {*} id
 * @returns
 */
export const fetchPermissionDetail = (id) => {
  return fetcher.get(`/identity/api/v1/permissions/${id}`)
}

/**
 *
 * @returns
 */
export const deletePermission = (id) => {
  return fetcher.delete(`/identity/api/v1/permissions/${id}`)
}
