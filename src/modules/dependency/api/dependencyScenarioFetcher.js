import fetcher from "@/modules/core/api/fetcher"

/**
 *
 * @param {*} payload
 * @returns
 */
export const createDependencySenario = async (payload) => {
  return fetcher.post(`identity/api/v1/dependency-scenarios`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const fetchDependencyScenario = async (uid) => {
  return fetcher.get(`identity/api/v1/dependency-scenarios/${uid}`)
}

/**
 *
 * @param {*} payload
 * @param {*} uid
 * @returns
 */
export const updateDependencyScenario = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/dependency-scenarios/${uid}`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const deleteDependencyScenario = async (uid) => {
  return fetcher.delete(`identity/api/v1/dependency-scenarios/${uid}`)
}

/**
 *
 * Get list resources
 * @returns
 */
export const fetchDependencyScenariosList = async () => {
  return fetcher.get(`/identity/api/v1/dependency-scenarios`)
}

/**
 * Download activities
 * @returns
 */
export const downloadDependencyScenarios = async () => {
  return fetcher.get(`/identity/api/v1/dependency-scenarios/download/all?`, {
    responseType: "blob",
  })
}
