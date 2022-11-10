import fetcher from "@/modules/core/api/fetcher"
import qs from "qs"

/**
 *
 * @param {*} filters
 * @returns
 */
export const fetchEventNotificationList = (filters) => {
  return fetcher.get("/identity/api/v1/event-notifications", {
    params: filters,
    paramsSerializer: (params) => {
      return qs.stringify(params)
    },
  })
}

/**
 *
 * @returns
 */
export const fetchEventNotificationConfigs = () => {
  return fetcher.get("identity/api/v1/event-notifications/configs")
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const fetchEventNotificationDetail = (uid) => {
  return fetcher.get(`/identity/api/v1/event-notifications/${uid}`)
}

/**
 *
 * @returns
 */
export const createEventNotification = (payload) => {
  return fetcher.post(`/identity/api/v1/event-notifications`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const updateEventNotification = (payload, uid) => {
  return fetcher.put(`/identity/api/v1/event-notifications/${uid}`, payload)
}

/**
 *
 * @param {*} uid
 * @returns
 */
export const deleteEventNotification = (uid) => {
  return fetcher.delete(`/identity/api/v1/event-notifications/${uid}`)
}
