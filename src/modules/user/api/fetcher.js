// Factory function returning fetcher suitable for CoverGo GraphQL APIs
import fetcher from "@/modules/core/api/fetcher"

export default fetcher

export const getUserList = async () => {
  return fetcher.get("/identity/api/v1/users")
}

export const getUserDetail = async (userId) => {
  return fetcher.get(`/identity/api/v1/users/${userId}`)
}

export const updateUser = async (userId, payload) => {
  return fetcher.put(`/identity/api/v1/users/${userId}`, payload)
}
