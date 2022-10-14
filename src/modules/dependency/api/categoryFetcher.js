import fetcher from "@/modules/core/api/fetcher"

export const fetchResourceCategoryList = async () => {
  return fetcher.get("identity/api/v1/resource-categories")
}

export const fetchResourceCategory = async (uid) => {
  return fetcher.get(`identity/api/v1/resource-categories/${uid}`)
}

export const updateCategory = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/resource-categories/${uid}`, payload)
}

export const deleteCategory = async (uid) => {
  return fetcher.delete(`identity/api/v1/resource-categories/${uid}`)
}

export const createResourceCategory = async (payload) => {
  return fetcher.post("identity/api/v1/resource-categories", payload)
}
