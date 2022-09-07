import * as adminApi from "../../api/adminBusinessUnitFetcher"
import * as api from "../../api/businessUnitFetcher"

export const useBusinessUnitList = () => {
  const adminGetBusinessUnitsByOrg = async (organizationUid) => {
    try {
      const { data } = await adminApi.fetchBusinessUnitnListByOrg(organizationUid)

      return data
    } catch (error) {}
  }

  const adminGetBusinessUnitsByDivision = async (organizationUid, divisionUid) => {
    try {
      const { data } = await adminApi.fetchBusinessUnitnList(organizationUid, divisionUid)

      return data
    } catch (error) {}
  }

  const tenantBusinessUnits = async () => {
    try {
      const { data } = await api.fetchDivision()

      return data
    } catch (error) {}
  }

  return {
    adminGetBusinessUnitsByOrg,
    adminGetBusinessUnitsByDivision,
    tenantBusinessUnits,
  }
}
