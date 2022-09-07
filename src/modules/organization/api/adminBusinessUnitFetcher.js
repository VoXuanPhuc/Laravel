import fetcher from "@/modules/core/api/fetcher"

export const fetchBusinessUnit = async (organizationUid, divisionUid, uid) => {
  return fetcher.get(`identity/api/v1/admin/organizations/${organizationUid}/divisions/${divisionUid}/business-units/${uid}`)
}

export const fetchBusinessUnitnList = async (organizationUid, divisionUid) => {
  return fetcher.get(`identity/api/v1/admin/organizations/${organizationUid}/divisions/${divisionUid}/business-units`)
}

export const fetchBusinessUnitnListByOrg = async (organizationUid) => {
  return fetcher.get(`identity/api/v1/admin/organizations/${organizationUid}/business-units`)
}

export const createBusinessUnit = async (payload, organizationUid, divisionUid) => {
  return fetcher.post(`identity/api/v1/admin/organizations/${organizationUid}/divisions/${divisionUid}/business-units`, payload)
}

export const updateBusinessUnit = async (payload, organizationUid, divisionUid, uid) => {
  return fetcher.put(
    `identity/api/v1/admin/organizations/${organizationUid}/divisions/${divisionUid}/business-units/${uid}`,
    payload
  )
}

export const deleteBusinessUnit = async (organizationUid, divisionUid, uid) => {
  return fetcher.delete(`identity/api/v1/admin/organizations/${organizationUid}/divisions/${divisionUid}/business-units/${uid}`)
}
