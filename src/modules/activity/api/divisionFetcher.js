import fetcher from "@/modules/core/api/fetcher"

export const fetchDivisionList = async () => {
  return fetcher.get("identity/api/v1/divisions")
}
