import fetcher from "@/modules/core/api/fetcher"

const fetchConfigs = async function () {
  return fetcher.get("/identity/api/v1/configs")
}

export { fetchConfigs }
