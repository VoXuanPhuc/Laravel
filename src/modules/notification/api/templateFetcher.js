import fetcher from "@/modules/core/api/fetcher"
import qs from "qs"

/**
 *
 * @param {*} filters
 * @returns
 */
export const fetchTemplateList = (filters) => {
  return fetcher.get("/identity/api/v1/notification-templates", {
    params: filters,
    paramsSerializer: (params) => {
      return qs.stringify(params)
    },
  })
}

/**
 *
 * @param {*} filters
 * @returns
 */
export const fetchTemplateListAll = (filters) => {
  return fetcher.get("/identity/api/v1/notification-templates/all")
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const fetchTemplateDetail = (uid) => {
  return fetcher.get(`/identity/api/v1/notification-templates/${uid}`)
}

/**
 *
 * @returns
 */
export const createTemplate = (payload) => {
  return fetcher.post(`/identity/api/v1/notification-templates`, payload)
}

/**
 *
 * @param {*} payload
 * @param {*} uid
 * @returns
 */
export const updateTemplate = (payload, uid) => {
  return fetcher.put(`/identity/api/v1/notification-templates/${uid}`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const deleteTemplate = (uid) => {
  return fetcher.delete(`/identity/api/v1/notification-templates/${uid}`)
}
