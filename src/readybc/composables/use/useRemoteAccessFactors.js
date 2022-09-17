import * as api from "@/readybc/composables/api/apiRemoteAccessFactors"

export function useRemoteAccessFactors() {
  /**
   *
   * @returns
   */
  const getRemoteAccessFactors = async () => {
    try {
      const { data } = await api.fetchRemoteAccessFactors()
      return data
    } catch (error) {
      return error
    }
  }

  return {
    getRemoteAccessFactors,
  }
}
