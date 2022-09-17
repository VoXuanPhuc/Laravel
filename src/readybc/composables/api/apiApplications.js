import fetcher from "@/modules/core/api/fetcher"

const fetchApplications = async function () {
  return fetcher.get("/identity/api/v1/applications")
}

export { fetchApplications }
