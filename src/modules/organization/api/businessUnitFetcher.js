import fetcher from "@/modules/core/api/fetcher"

export const fetchBusinessUnit = async (divisionUid, uid) => {
  return fetcher.get(`identity/api/v1//divisions/${divisionUid}/business-units/${uid}`)
}

export const fetchBusinessUnitList = async (divisionUid) => {
  return fetcher.get(`identity/api/v1/divisions/${divisionUid}/business-units`)
}

export const fetchBusinessUnitListByOrg = async () => {
  return fetcher.get(`identity/api/v1/business-units`)
}

export const createBusinessUnit = async (payload, divisionUid) => {
  return fetcher.post(`identity/api/v1/divisions/${divisionUid}/business-units`, payload)
}

export const updateBusinessUnit = async (payload, divisionUid, uid) => {
  return fetcher.put(`identity/api/v1/divisions/${divisionUid}/divisions/${uid}`, payload)
}

export const deleteBusinessUnit = async (divisionUid, uid) => {
  return fetcher.delete(`identity/api/v1/divisions/${divisionUid}/divisions/${uid}`)
}
