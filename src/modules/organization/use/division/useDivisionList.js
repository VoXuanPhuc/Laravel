import * as adminApi from "../../api/adminDivisionFetcher"
import * as api from "../../api/divisionFetcher"

export const useDivisionList = () => {
  const adminGetDivisions = async (organizationUid) => {
    try {
      const { data } = await adminApi.fetchDivisionList(organizationUid)

      return data
    } catch (error) {}
  }

  const tenantGetDivisions = async () => {
    try {
      const { data } = await api.fetchDivisionList()

      return data
    } catch (error) {}
  }

  return { adminGetDivisions, tenantGetDivisions }
}
