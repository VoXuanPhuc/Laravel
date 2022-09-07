import fetcher from "@/modules/core/api/fetcher"

const fetchIndustries = async function () {
  return fetcher.get("identity/api/v1/admin/industries")
}

export { fetchIndustries }
