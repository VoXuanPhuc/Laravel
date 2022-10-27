import fetcher from "@/modules/core/api/fetcher"
import qs from "qs"

/**
 *
 * @param {*} payload
 * @returns
 */
export const createNewBIA = async (payload) => {
  return fetcher.post(`identity/api/v1/bias`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const fetchBIA = async (uid) => {
  return fetcher.get(`identity/api/v1/bias/${uid}`)
}

/**
 *
 * @param {*} payload
 * @param {*} uid
 * @returns
 */
export const updateBIA = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/bias/${uid}`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const deleteBIA = async (uid) => {
  return fetcher.delete(`identity/api/v1/bias/${uid}`)
}

/**
 *
 * Get list BIA
 * @returns
 */
export const fetchBIAList = async (filters) => {
  return fetcher.get(`/identity/api/v1/bias`, {
    params: filters,
    paramsSerializer: (params) => {
      return qs.stringify(params)
    },
  })
}

/**
 * Download activities
 * @returns
 */
export const downloadBIAs = async (filters) => {
  return fetcher.get(`/identity/api/v1/bias/download/all`, {
    params: filters,
    paramsSerializer: (params) => {
      return qs.stringify(params)
    },
    responseType: "blob",
  })
}

/**
 *
 * @param {*} uid
 * @returns
 */

export const exportBIA = async (uid) => {
  return fetcher.get(`/identity/api/v1/bias/${uid}/export`, {
    responseType: "blob",
  })
}

/**
 * Get Activity Logs
 *
 * @param {*} uid
 * @returns
 */

export const fetBIALogs = async (uid) => {
  return fetcher.get(`/identity/api/v1/bias/${uid}/logs`)
}
