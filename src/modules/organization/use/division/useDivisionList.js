import * as api from "../../api/divisionFetcher"

export const useDivisionList = () => {
  const getDivisions = async () => {
    try {
      const { data } = await api.fetchDivisionList()

      return data
    } catch (error) {}
  }

  return { getDivisions }
}
