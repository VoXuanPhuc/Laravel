import fetcher from "@/modules/core/api/fetcher"

export const fetchCertificateList = (supplierUid) => {
  return fetcher.get(`identity/api/v1/suppliers/${supplierUid}/certs`)
}

export const fetchCertificate = (supplierUid, certificateUid) => {
  return fetcher.get(`identity/api/v1/suppliers/${supplierUid}/certs/${certificateUid}`)
}

export const createCertificate = (supplierUid, payload) => {
  return fetcher.post(`identity/api/v1/suppliers/${supplierUid}/certs`, payload)
}

export const deleteCertificate = (supplierUid, certificateUid) => {
  return fetcher.delete(`identity/api/v1/suppliers/${supplierUid}/certs/${certificateUid}`)
}
