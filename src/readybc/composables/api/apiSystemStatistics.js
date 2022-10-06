import fetcher from "@/modules/core/api/fetcher"

const fetchSystemStatistics = async function () {
  return fetcher.get("/identity/api/v1/admin/system-statistics")
}

const fetchTenantSystemStatistics = async function () {
  return fetcher.get("/identity/api/v1/system-statistics")
}

export { fetchSystemStatistics, fetchTenantSystemStatistics }
