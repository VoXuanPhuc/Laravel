import * as api from "../../api/businessUnitFetcher"

export const useBusinessUnitList = () => {
  /**
   *
   * @param {*} organizationUid
   * @returns
   */
  const getBusinessUnits = async () => {
    try {
      const { data } = await api.fetchBusinessUnitList()

      return data
    } catch (error) {}
  }

  /**
   *
   * @param {*} organizationUid
   * @param {*} divisionUid
   * @returns
   */
  const getBusinessUnitsByDivision = async (divisionUid) => {
    try {
      const { data } = await api.fetchBusinessUnitListByDivision(divisionUid)

      return data
    } catch (error) {}
  }

  return {
    getBusinessUnits,
    getBusinessUnitsByDivision,
  }
}
