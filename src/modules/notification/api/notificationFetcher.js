import fetcher from "@/modules/core/api/fetcher"

export const fetchNotificationLogs = () => {
  return fetcher.get(`/identity/api/v1/notifications`)
}

export const fetchNotificationLogDetail = (uid) => {
  return fetcher.get(`/identity/api/v1/notifications/logs/${uid}`)
}

export const markNotificationAsRead = (uid) => {
  return fetcher.patch(`/identity/api/v1/notifications/${uid}/mark-as-read`)
}
