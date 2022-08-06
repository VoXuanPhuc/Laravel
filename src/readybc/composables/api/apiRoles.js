import fetcher from "@/modules/core/api/fetcher"

export const getRoles = async function ({ variables, fragment }) {
  return fetcher.get("/identity/api/v1/roles")
}

export const createRole = async function ({ variables }) {
  return fetcher.post("/identity/api/v1/roles", variables)
}

export const updateRole = async function ({ roleId, variables }) {
  return fetcher.post(`/identity/api/v1/roles/${roleId}`, variables)
}

export const deleteRole = async function (roleId) {
  return fetcher.delete(`/identity/api/v1/roles/${roleId}`)
}
