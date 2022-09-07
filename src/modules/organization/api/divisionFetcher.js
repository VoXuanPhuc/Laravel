import fetcher from "@/modules/core/api/fetcher"

export const fetchDivision = async (organizationId) => {
  return fetcher.get(`identity/api/v1/divisions/${organizationId}`)
}

export const fetchDivisionList = async () => {
  return fetcher.get("identity/api/v1/divisions")
}

export const createDivision = async (payload) => {
  return fetcher.post("identity/api/v1/divisions", payload)
}

export const updateDivision = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/divisions/${uid}`, payload)
}

export const deleteDivision = async (uid) => {
  return fetcher.delete(`identity/api/v1/divisions/${uid}`)
}
