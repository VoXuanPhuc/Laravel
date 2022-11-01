import fetcher from "@/modules/core/api/fetcher"

export const fetchTopBIAs = () => {
  return fetcher.get("/identity/api/v1/bias/top")
}
