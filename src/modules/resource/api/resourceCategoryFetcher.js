import fetcher from "@/modules/core/api/fetcher"

export const fetchResourceCategoryList = async () => {
  return fetcher.get("identity/api/v1/resource-categories")
}
