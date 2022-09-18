import fetcher from "@/modules/core/api/fetcher"

export const login = (credential) => {
  return fetcher.post("/identity/api/v1/login", credential)
}
