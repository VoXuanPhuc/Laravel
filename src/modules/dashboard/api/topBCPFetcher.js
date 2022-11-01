import fetcher from "@/modules/core/api/fetcher"

export const fetchTopBCPs = () => {
  return fetcher.get("/identity/api/v1/bcps/top")
}
