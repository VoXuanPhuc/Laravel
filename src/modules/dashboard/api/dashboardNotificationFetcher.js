import fetcher from "@/modules/core/api/fetcher"

export const fetchDashboardNotifications = () => {
  return fetcher.get("/identity/api/v1/notifications/dashboard")
}
