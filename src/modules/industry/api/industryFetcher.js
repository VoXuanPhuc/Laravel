import fetcher from "@/modules/core/api/fetcher"

export const fetchIndustryList = async () => {
  return fetcher.get(`identity/api/v1/admin/industries`)
}

export const fetchIndustryDetail = async (industryUid) => {
  return fetcher.get(`identity/api/v1/admin/industries/${industryUid}`)
}

export const updateIndustry = async (industryUid, payload) => {
  return fetcher.put(`identity/api/v1/admin/industries/${industryUid}`, payload)
}

export const createIndustry = async (payload) => {
  return fetcher.post(`identity/api/v1/admin/industries`, payload)
}

export const deleteIndustry = async (industryUid) => {
  return fetcher.delete(`identity/api/v1/admin/industries/${industryUid}`)
}
