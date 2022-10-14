import fetcher from "@/modules/core/api/fetcher"

/**
 *
 * @param {*} payload
 * @returns
 */
export const createNewResource = async (payload) => {
  return fetcher.post(`identity/api/v1/resources`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const fetchResource = async (uid) => {
  return fetcher.get(`identity/api/v1/resources/${uid}`)
}

/**
 *
 * @param {*} payload
 * @param {*} uid
 * @returns
 */
export const updateResource = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/resources/${uid}`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const deleteResource = async (uid) => {
  return fetcher.delete(`identity/api/v1/resources/${uid}`)
}

/**
 *
 * Get list resources
 * @returns
 */
export const fetchResourceList = async () => {
  return fetcher.get(`/identity/api/v1/resources`)
}

/**
 * Download activities
 * @returns
 */
export const downloadResources = async (categoryUid) => {
  var query = new URLSearchParams()
  query.append("categoryUid", categoryUid)

  return fetcher.get(`/identity/api/v1/resources/download/all?` + query.toString(), {
    responseType: "blob",
  })
}
