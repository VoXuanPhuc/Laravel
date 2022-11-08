// Factory function returning fetcher suitable for CoverGo GraphQL APIs
import fetcher from "@/modules/core/api/fetcher"

export const getUserList = async () => {
  return fetcher.get("/identity/api/v1/users")
}

export const getUserDetail = async (userId) => {
  return fetcher.get(`/identity/api/v1/users/${userId}`)
}

export const createUser = async (payload) => {
  return fetcher.post("identity/api/v1/admin/users", payload)
}

export const deleteUser = async (id) => {
  return fetcher.delete(`identity/api/v1/admin/users/${id}`)
}

export const updateUser = async (userId, payload) => {
  return fetcher.put(`/identity/api/v1/users/${userId}`, payload)
}

export const assignRole = async (userId, payload) => {
  return fetcher.patch(`/identity/api/v1/users/${userId}/roles`, payload)
}

export const reinviteUser = async (userId) => {
  return fetcher.post(`/identity/api/v1/users/${userId}/reinvite`)
}
