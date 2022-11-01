import fetcher from "@/modules/core/api/fetcher"

export const fetchTopActivities = () => {
  return fetcher.get("/identity/api/v1/activities/top")
}
