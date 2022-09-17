import fetcher from "@/modules/core/api/fetcher"

const fetchRemoteAccessFactors = async function () {
  return fetcher.get("/identity/api/v1/remote-access-factors")
}

export { fetchRemoteAccessFactors }
