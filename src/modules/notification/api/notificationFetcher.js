import fetcher from "@/modules/core/api/fetcher"

export const markNotificationAsRead = (uid) => {
  return fetcher.patch(`/identity/api/v1/notifications/${uid}/mark-as-read`)
}
