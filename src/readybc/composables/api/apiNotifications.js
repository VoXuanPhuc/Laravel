import fetcher from "@/modules/core/api/fetcher"

const apiNotifications = async function ({ variables, fragment }) {
  return fetcher.get("/notifications")
}

export { apiNotifications }
