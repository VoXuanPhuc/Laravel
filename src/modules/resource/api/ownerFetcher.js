import fetcher from "@/modules/core/api/fetcher"

export const fetchOwnerList = async () => {
  return fetcher.get("identity/api/v1/resource-owners")
}
