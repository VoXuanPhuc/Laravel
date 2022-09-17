import fetcher from "@/modules/core/api/fetcher"

const fetchUtilities = async function () {
  return fetcher.get("/identity/api/v1/utilities")
}

export { fetchUtilities }
