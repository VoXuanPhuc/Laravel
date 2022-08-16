import fetcher from "@/modules/core/api/fetcher"

/**
 * Get Roles
 * @returns
 */
export const fetchRoles = async () => {
  return fetcher.get("/identity/api/v1/roles")
}

/**
 * Create new Role
 * @param {*} payload
 * @returns
 */
export const createRole = async (payload) => {
  return fetcher.post("/identity/api/v1/roles", payload)
}

/**
 * Update Role
 * @param {*} uid
 * @param {*} payload
 * @returns
 */
export const updateRole = async (uid, payload) => {
  return fetcher.post(`/identity/api/v1/roles/${uid}`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const fetchRoleDetail = (uid) => {
  return fetcher.get(`/identity/api/v1/roles/${uid}`)
}

/**
 *
 * @returns
 */
export const deleteRole = (uid) => {
  return fetcher.delete(`/identity/api/v1/roles/${uid}`)
}
