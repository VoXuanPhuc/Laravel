import * as api from "@/readybc/composables/api/apiUtilities"

export function useUtilities() {
  /**
   *
   * @returns
   */
  const getUtilities = async () => {
    try {
      const { data } = await api.fetchUtilities()
      return data
    } catch (error) {
      return error
    }
  }

  return {
    getUtilities,
  }
}
