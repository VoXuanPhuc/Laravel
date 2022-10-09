import fetcher from "@/modules/core/api/fetcher"

export const fetchCategoryList = () => {
  return fetcher.get("identity/api/v1/supplier-categories")
}

export const createCategory = (payload) => {
  return fetcher.post("/identity/api/v1/supplier-categories", payload)
}
