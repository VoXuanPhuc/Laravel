import fetcher from "@/modules/core/api/fetcher"

export const getPermissions = async function ({ variables, fragment }) {
  return fetcher.get("/identity/api/v1/permissions")
}
