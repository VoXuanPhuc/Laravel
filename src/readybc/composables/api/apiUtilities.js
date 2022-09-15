import fetcher from "@/modules/core/api/fetcher"

const fetchUtilities = async function () {
  // return api.get("identity/api/v1/utilities")
  return fetcher.get("/identity/api/v1/roles")
}

export { fetchUtilities }
