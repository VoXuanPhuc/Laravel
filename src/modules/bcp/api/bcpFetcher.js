import fetcher from "@/modules/core/api/fetcher"
import qs from "qs"

/**
 *
 * @param {*} payload
 * @returns
 */
export const createNewBCP = async (payload) => {
  return fetcher.post(`identity/api/v1/bcps`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const fetchBCP = async (uid) => {
  return fetcher.get(`identity/api/v1/bcps/${uid}`)
}

/**
 *
 * @param {*} payload
 * @param {*} uid
 * @returns
 */
export const updateBCP = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/bcps/${uid}`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const deleteBCP = async (uid) => {
  return fetcher.delete(`identity/api/v1/bcps/${uid}`)
}

/**
 *
 * Get list BCP
 * @returns
 */
export const fetchBCPList = async (filters) => {
  const query = "?" + qs.stringify(filters)
  return fetcher.get(`/identity/api/v1/bcps` + query)
}

/**
 * Download activities
 * @returns
 */
export const downloadBCPs = async (categoryUid) => {
  var query = new URLSearchParams()
  query.append("categoryUid", categoryUid)

  return fetcher.get(`/identity/api/v1/bcps/download/all?` + query.toString(), {
    responseType: "blob",
  })
}
