import fetcher from "@/modules/core/api/fetcher"

export const fetchOwnerList = async () => {
  return fetcher.get("identity/api/v1/resource-owners")
}

export const fetchOwnerListAll = async () => {
  return fetcher.get("identity/api/v1/resource-owners/all")
}

export const fetchOwner = async (uid) => {
  return fetcher.get(`identity/api/v1/resource-owners/${uid}`)
}

export const createOwner = async (payload) => {
  return fetcher.post(`identity/api/v1/resource-owners`, payload)
}

export const updateOwner = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/resource-owners/${uid}`, payload)
}

export const deleteOwner = async (uid) => {
  return fetcher.delete(`identity/api/v1/resource-owners/${uid}`)
}
