import fetcher from "@/modules/core/api/fetcher"

export const fetchDivisionList = async (organizationUid) => {
  return fetcher.get(`identity/api/v1/admin/organizations/${organizationUid}/divisions`)
}

export const fetchDivision = async (organizationUid, uid) => {
  return fetcher.get(`identity/api/v1/admin/organizations/${organizationUid}/divisions/${uid}`)
}

export const createDivision = async (organizationUid, payload) => {
  return fetcher.post(`identity/api/v1/admin/organizations/${organizationUid}/divisions`, payload)
}

export const updateDivision = async (payload, organizationUid, uid) => {
  return fetcher.put(`identity/api/v1/admin/organizations/${organizationUid}/divisions/${uid}`, payload)
}

export const deleteDivision = async (organizationUid, uid) => {
  return fetcher.delete(`identity/api/v1/admin/organizations/${organizationUid}/divisions/${uid}`)
}
