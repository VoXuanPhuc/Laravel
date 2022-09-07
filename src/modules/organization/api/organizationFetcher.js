import fetcher from "@/modules/core/api/fetcher"

export const fetchOrganization = async (organizationId) => {
  return fetcher.get(`identity/api/v1/admin/organizations/${organizationId}`)
}

export const fetchOrganizationList = async () => {
  return fetcher.get("identity/api/v1/admin/organizations")
}

export const createOrganization = async (payload) => {
  return fetcher.post("identity/api/v1/admin/organizations", payload)
}

export const updateOrganization = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/admin/organizations/${uid}`, payload)
}

export const deleteOrganization = async (uid) => {
  return fetcher.delete(`identity/api/v1/admin/organizations/${uid}`)
}
