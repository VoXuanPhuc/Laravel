import fetcher from "@/modules/core/api/fetcher"

export const fetchCategoryList = () => {
  return fetcher.get("identity/api/v1/supplier-categories")
}

export const fetchCategoryDetail = (supplierUid) => {
  return fetcher.get(`identity/api/v1/supplier-categories/${supplierUid}`)
}

export const createCategory = (payload) => {
  return fetcher.post("/identity/api/v1/supplier-categories", payload)
}

export const updateCategory = (supplierUid, payload) => {
  return fetcher.put(`/identity/api/v1/supplier-categories/${supplierUid}`, payload)
}

export const deleteCategory = (supplierUid) => {
  return fetcher.delete(`/identity/api/v1/supplier-categories/${supplierUid}`)
}
