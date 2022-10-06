import fetcher from "@/modules/core/api/fetcher"

/**
 * Fetch individual BU
 * @param {*} divisionUid
 * @param {*} uid
 * @returns
 */
export const fetchBusinessUnit = async (divisionUid, uid) => {
  return fetcher.get(`identity/api/v1/divisions/${divisionUid}/business-units/${uid}`)
}

/**
 * Fetch all by tenant
 * @returns
 */
export const fetchBusinessUnitList = async () => {
  return fetcher.get(`identity/api/v1/business-units`)
}

/**
 * Fetch BU by division
 * @param {*} divisionUid
 * @returns
 */
export const fetchBusinessUnitListByDivision = async (divisionUid) => {
  return fetcher.get(`identity/api/v1/divisions/${divisionUid}/business-units`)
}

/**
 * Create BU
 * @param {*} payload
 * @param {*} divisionUid
 * @returns
 */
export const createBusinessUnit = async (payload, divisionUid) => {
  return fetcher.post(`identity/api/v1/divisions/${divisionUid}/business-units`, payload)
}

/**
 * Update BU
 * @param {*} payload
 * @param {*} divisionUid
 * @param {*} uid
 * @returns
 */
export const updateBusinessUnit = async (payload, divisionUid, uid) => {
  return fetcher.put(`identity/api/v1/divisions/${divisionUid}/business-units/${uid}`, payload)
}

/**
 * Delete BU
 * @param {*} divisionUid
 * @param {*} uid
 * @returns
 */
export const deleteBusinessUnit = async (divisionUid, uid) => {
  return fetcher.delete(`identity/api/v1/divisions/${divisionUid}/business-units/${uid}`)
}
