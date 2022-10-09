import fetcher from "@/modules/core/api/fetcher"

export const fetchSupplierList = async () => {
  return fetcher.get(`identity/api/v1/suppliers`)
}

export const fetchSupplier = async (uid) => {
  return fetcher.get(`identity/api/v1/suppliers/${uid}`)
}

export const createSupplier = async (payload) => {
  return fetcher.post(`identity/api/v1/suppliers`, payload)
}

export const updateSupplier = async (supplierUid, payload) => {
  return fetcher.put(`identity/api/v1/suppliers/${supplierUid}`, payload)
}

export const deleteSupplier = async (uid) => {
  return fetcher.delete(`identity/api/v1/suppliers/${uid}`)
}

export const downloadSuppliers = async () => {
  return fetcher.get(`identity/api/v1/suppliers/download/all`, {
    responseType: "blob",
  })
}
